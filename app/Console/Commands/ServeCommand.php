<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

/**
 * Laravel's built-in server spawns a child `php -S` process that does not inherit
 * CLI `-c` flags from the parent. Apply upload limits on the worker that handles HTTP.
 */
class ServeCommand extends BaseServeCommand
{
    /**
     * @return array<int, string>
     */
    protected function serverCommand()
    {
        $command = parent::serverCommand();

        $ini = base_path('scripts/php-upload-limits.ini');

        if (is_readable($ini)) {
            array_splice($command, 1, 0, ['-c', $ini]);
        }

        return $command;
    }
}
