<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageAttachments
{
    public static function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'required_without:attachment'],
            'attachment' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'required_without:content'],
        ];
    }

    public static function store(?UploadedFile $file, ?int $tenantId): ?string
    {
        if (! $file) {
            return null;
        }

        $folder = 'messages/'.($tenantId ?: 'central');

        return $file->store($folder, 'public');
    }

    public static function delete(?string $path): void
    {
        $path = trim((string) $path);

        if ($path === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public static function excerpt(?string $content, ?string $attachmentPath, int $limit = 140): string
    {
        $text = trim(strip_tags((string) $content));

        if ($text !== '') {
            return Str::limit($text, $limit);
        }

        if (trim((string) $attachmentPath) !== '') {
            return 'Photo attachment';
        }

        return '';
    }
}
