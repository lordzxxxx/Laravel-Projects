<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

class MunicipalityDocumentUploads
{
    public const FIELDS = [
        'business_permit',
        'mayors_permit',
        'barangay_clearance',
        'valid_id',
    ];

    public const DISK = 'public';

    public const DIRECTORY = 'owner-municipality-docs';

    public const MAX_KILOBYTES = 10240;

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        $fileRule = File::types(['pdf', 'jpg', 'jpeg', 'png'])
            ->max(self::MAX_KILOBYTES);

        $rules = [];
        foreach (self::FIELDS as $field) {
            $rules[$field] = ['required', $fileRule];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'business_permit.required' => 'Attach your municipality business permit or equivalent credential.',
            'business_permit.max' => 'Compress or split uploads so each attachment stays below ten megabytes.',
            'mayors_permit.required' => 'Attach your mayor\'s permit (or analogous executive authorization).',
            'mayors_permit.max' => 'Mayor\'s permit uploads must remain under ten megabytes.',
            'barangay_clearance.required' => 'Attach the barangay clearance currently in force.',
            'barangay_clearance.max' => 'Clearance files must remain under ten megabytes.',
            'valid_id.required' => 'Attach a readable government identification document.',
            'valid_id.max' => 'Identification uploads must remain under ten megabytes.',
            'business_permit.mimes' => 'Business permits must upload as PDF, JPEG, or PNG.',
            'business_permit.extensions' => 'Business permits must upload as PDF, JPEG, or PNG.',
            'mayors_permit.mimes' => 'Mayor\'s permit uploads accept PDF, JPEG, or PNG only.',
            'mayors_permit.extensions' => 'Mayor\'s permit uploads accept PDF, JPEG, or PNG only.',
            'barangay_clearance.mimes' => 'Clearance uploads accept PDF, JPEG, or PNG only.',
            'barangay_clearance.extensions' => 'Clearance uploads accept PDF, JPEG, or PNG only.',
            'valid_id.mimes' => 'Identification uploads accept PDF, JPEG, or PNG.',
            'valid_id.extensions' => 'Identification uploads accept PDF, JPEG, or PNG.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function attributeLabels(): array
    {
        return [
            'business_permit' => 'business permit',
            'mayors_permit' => "mayor's permit",
            'barangay_clearance' => 'barangay clearance',
            'valid_id' => 'government ID',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function validate(Request $request): array
    {
        self::assertNotPostSizeExceeded($request);
        self::assertEachUploadHealthy($request);

        return $request->validate(self::rules(), self::messages(), self::attributeLabels());
    }

    /**
     * @return array{
     *     municipality_business_permit_path: string,
     *     municipality_mayors_permit_path: string,
     *     municipality_barangay_clearance_path: string,
     *     municipality_valid_id_path: string,
     * }
     */
    public static function storeAll(Request $request): array
    {
        $paths = [];

        try {
            foreach (self::FIELDS as $field) {
                $file = $request->file($field);
                if (! $file instanceof UploadedFile) {
                    throw ValidationException::withMessages([
                        $field => ['No file was received. Select the document again and submit.'],
                    ]);
                }

                $paths[$field] = $file->store(self::DIRECTORY, self::DISK);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            self::deleteStoredPaths($paths);

            throw ValidationException::withMessages([
                'business_permit' => ['We could not save your documents. Try again or contact support if this continues.'],
            ]);
        }

        return [
            'municipality_business_permit_path' => $paths['business_permit'],
            'municipality_mayors_permit_path' => $paths['mayors_permit'],
            'municipality_barangay_clearance_path' => $paths['barangay_clearance'],
            'municipality_valid_id_path' => $paths['valid_id'],
        ];
    }

    /**
     * @param  array<int, string>  $paths
     */
    public static function deleteStoredPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk(self::DISK)->delete($path);
            }
        }
    }

    private static function assertNotPostSizeExceeded(Request $request): void
    {
        if (! self::likelyPostSizeExceeded($request)) {
            return;
        }

        throw ValidationException::withMessages([
            'business_permit' => [
                'The combined size of all four documents exceeds the server upload limit ('.self::formatIniSize(self::postMaxBytes()).'). '
                .'Use smaller files (under 10 MB each) or compress your scans, then try again.',
            ],
        ]);
    }

    private static function assertEachUploadHealthy(Request $request): void
    {
        $errors = [];

        foreach (self::FIELDS as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            if (! $file instanceof UploadedFile) {
                continue;
            }

            if ($file->isValid()) {
                $extension = strtolower((string) $file->getClientOriginalExtension());
                if (in_array($extension, ['heic', 'heif'], true)) {
                    $errors[$field][] = 'iPhone HEIC photos are not supported. Export or save the document as PDF, JPEG, or PNG, then upload again.';
                }

                continue;
            }

            $errors[$field][] = self::uploadErrorMessage($file);
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    public static function uploadErrorMessage(UploadedFile $file): string
    {
        return match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE => 'This file exceeds the server per-file upload limit ('.self::formatIniSize(self::uploadMaxBytes()).'). Use a smaller PDF or image.',
            UPLOAD_ERR_FORM_SIZE => 'This file exceeds the form upload limit. Use a smaller PDF or image (under 10 MB).',
            UPLOAD_ERR_PARTIAL => 'The upload was interrupted. Select the file again and submit.',
            UPLOAD_ERR_NO_FILE => 'No file was received. Select the document again and submit.',
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE, UPLOAD_ERR_EXTENSION => 'The server could not store this upload. Try again later or contact support.',
            default => 'The file could not be uploaded. Use PDF, JPEG, or PNG under 10 MB, then try again.',
        };
    }

    public static function likelyPostSizeExceeded(Request $request): bool
    {
        if (! $request->isMethod('POST')) {
            return false;
        }

        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        if ($contentLength <= 0) {
            return false;
        }

        $postMax = self::postMaxBytes();
        if ($contentLength <= $postMax) {
            return false;
        }

        $contentType = strtolower((string) $request->header('Content-Type', ''));
        if (! str_contains($contentType, 'multipart')) {
            return false;
        }

        foreach (self::FIELDS as $field) {
            $file = $request->file($field);
            if ($file instanceof UploadedFile && $file->isValid()) {
                return false;
            }
        }

        return true;
    }

    public static function postMaxBytes(): int
    {
        return self::parseIniSize((string) ini_get('post_max_size'));
    }

    public static function uploadMaxBytes(): int
    {
        return self::parseIniSize((string) ini_get('upload_max_filesize'));
    }

    public static function parseIniSize(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return (int) match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => (float) $value,
        };
    }

    public static function formatIniSize(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return round($bytes / (1024 * 1024), 1).' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024).' KB';
        }

        return $bytes.' bytes';
    }
}
