<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class FileIconResolver
{
    /**
     * @return array{icon: string, color: string}
     */
    public static function resolve(?string $filename): array
    {
        $ext = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));

        return match (true) {
            in_array($ext, ['pdf']) => ['icon' => 'heroicon-o-document-text', 'color' => 'text-red-500'],
            in_array($ext, ['doc', 'docx', 'rtf', 'odt']) => ['icon' => 'heroicon-o-document-text', 'color' => 'text-blue-500'],
            in_array($ext, ['xls', 'xlsx', 'csv', 'ods']) => ['icon' => 'heroicon-o-table-cells', 'color' => 'text-emerald-500'],
            in_array($ext, ['ppt', 'pptx', 'odp']) => ['icon' => 'heroicon-o-presentation-chart-bar', 'color' => 'text-orange-500'],
            in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'avif']) => ['icon' => 'heroicon-o-photo', 'color' => 'text-pink-500'],
            in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz']) => ['icon' => 'heroicon-o-archive-box', 'color' => 'text-yellow-600'],
            in_array($ext, ['mp3', 'wav', 'ogg', 'flac', 'm4a']) => ['icon' => 'heroicon-o-musical-note', 'color' => 'text-purple-500'],
            in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm']) => ['icon' => 'heroicon-o-film', 'color' => 'text-indigo-500'],
            in_array($ext, ['txt', 'md', 'log']) => ['icon' => 'heroicon-o-document', 'color' => 'text-gray-500'],
            default => ['icon' => 'heroicon-o-document', 'color' => 'text-gray-400'],
        };
    }

    public static function formatBytes(?int $bytes): ?string
    {
        if ($bytes === null) {
            return null;
        }

        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1048576) {
            return number_format($bytes / 1024, 1).' KB';
        }
        if ($bytes < 1073741824) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        return number_format($bytes / 1073741824, 2).' GB';
    }

    public static function humanSize(string $disk, ?string $path): ?string
    {
        if (! $path || ! Storage::disk($disk)->exists($path)) {
            return null;
        }

        return self::formatBytes(Storage::disk($disk)->size($path));
    }
}
