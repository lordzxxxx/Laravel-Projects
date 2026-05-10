<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SingleDbQueryBridge
{
    /**
     * @template T
     *
     * @param  callable():T  $singleDbRead
     * @param  null|callable():T  $legacyRead
     * @return T
     */
    public function read(callable $singleDbRead, ?callable $legacyRead = null, string $scope = 'unknown')
    {
        if (SingleDbMigrationMode::readsEnabled()) {
            $result = $singleDbRead();

            if (SingleDbMigrationMode::shadowReadsEnabled() && $legacyRead !== null) {
                try {
                    $legacy = $legacyRead();
                    $singleCount = $this->countableSize($result);
                    $legacyCount = $this->countableSize($legacy);
                    if ($singleCount !== null && $legacyCount !== null && $singleCount !== $legacyCount) {
                        Log::warning('Single DB shadow read mismatch.', [
                            'scope' => $scope,
                            'single_count' => $singleCount,
                            'legacy_count' => $legacyCount,
                        ]);
                        $this->recordShadowMismatch($scope, $singleCount, $legacyCount);
                    }
                } catch (\Throwable $exception) {
                    Log::warning('Single DB shadow read failed.', [
                        'scope' => $scope,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            return $result;
        }

        return $legacyRead ? $legacyRead() : $singleDbRead();
    }

    public function write(callable $singleDbWrite, ?callable $legacyWrite = null, string $scope = 'unknown')
    {
        if (SingleDbMigrationMode::writesEnabled()) {
            $result = $singleDbWrite();

            if ($legacyWrite !== null) {
                try {
                    $legacyWrite();
                } catch (\Throwable $exception) {
                    Log::warning('Dual-write legacy path failed during single DB migration.', [
                        'scope' => $scope,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            return $result;
        }

        return $legacyWrite ? $legacyWrite() : $singleDbWrite();
    }

    private function recordShadowMismatch(string $scope, int $singleCount, int $legacyCount): void
    {
        try {
            DB::connection('landlord')->table('update_logs')->insert([
                'tenant_id' => null,
                'user_id' => null,
                'current_version' => 'single-db-shadow',
                'latest_version' => $scope,
                'release_notes' => null,
                'download_url' => null,
                'channel_status' => 'single_db_shadow_mismatch',
                'status_message' => "single={$singleCount}, legacy={$legacyCount}",
                'checked_at' => now(),
                'installed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Failed to persist single DB shadow mismatch log.', [
                'scope' => $scope,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function countableSize(mixed $value): ?int
    {
        if (is_countable($value)) {
            return count($value);
        }

        return null;
    }
}
