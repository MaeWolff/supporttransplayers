<?php

namespace App\Support;

class MediaAttachmentData
{
    /**
     * @return array<string, mixed>|null
     */
    public static function resolve(int $attachmentId): ?array
    {
        if ($attachmentId <= 0) {
            return null;
        }

        $path = get_attached_file($attachmentId);

        if (! is_string($path) || $path === '' || ! file_exists($path)) {
            return null;
        }

        $url = wp_get_attachment_url($attachmentId);

        if (! is_string($url) || $url === '') {
            return null;
        }

        $mime = get_post_mime_type($attachmentId) ?: '';
        $filename = basename($path);
        $extension = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
        $isImage = str_starts_with($mime, 'image/');
        $thumbUrl = null;

        if ($isImage) {
            $thumb = wp_get_attachment_image_src($attachmentId, 'medium');

            if (is_array($thumb) && isset($thumb[0])) {
                $thumbUrl = $thumb[0];
            }
        }

        return [
            'id' => $attachmentId,
            'url' => $url,
            'filename' => $filename,
            'extension' => $extension,
            'mime' => $mime,
            'isImage' => $isImage,
            'thumbUrl' => $thumbUrl,
            'filesize' => filesize($path) ?: 0,
            'filesizeLabel' => self::formatFilesize(filesize($path) ?: 0),
        ];
    }

    public static function formatFilesize(int $bytes): string
    {
        if ($bytes <= 0) {
            return '';
        }

        $units = ['o', 'Ko', 'Mo', 'Go'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return number_format($value, $power > 0 ? 1 : 0, ',', ' ').' '.$units[$power];
    }
}
