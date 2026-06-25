<?php

namespace App\Downloads;

use App\Support\BlockLocales;
use App\Support\KitData;
use App\Support\MediaAttachmentData;

class ZipBuilder
{
    public static function cacheDir(): string
    {
        $upload = wp_upload_dir();
        $dir = trailingslashit($upload['basedir']).'stp-downloads';

        if (! is_dir($dir)) {
            wp_mkdir_p($dir);
        }

        return $dir;
    }

    /**
     * @param  array<int, int>  $attachmentIds
     */
    public static function fingerprint(int $postId, string $lang, array $attachmentIds): string
    {
        $parts = [(string) $postId, $lang];

        foreach ($attachmentIds as $attachmentId) {
            $path = get_attached_file($attachmentId);
            $mtime = is_string($path) && $path !== '' && file_exists($path)
                ? (string) filemtime($path)
                : '0';

            $parts[] = $attachmentId.':'.$mtime;
        }

        return md5(implode('|', $parts));
    }

    /**
     * @param  array<int, int>  $attachmentIds
     */
    public static function buildOrServe(int $postId, string $lang, array $attachmentIds, string $downloadName): void
    {
        if (! class_exists(\ZipArchive::class)) {
            wp_die(
                esc_html__('L’extension PHP ZipArchive est requise pour générer le kit.', 'sage'),
                esc_html__('Téléchargement indisponible', 'sage'),
                ['response' => 500]
            );
        }

        if ($attachmentIds === []) {
            wp_die(
                esc_html__('Aucun fichier disponible pour ce kit.', 'sage'),
                esc_html__('Téléchargement indisponible', 'sage'),
                ['response' => 404]
            );
        }

        $fingerprint = self::fingerprint($postId, $lang, $attachmentIds);
        $cachePath = self::cacheDir().'/'.$fingerprint.'.zip';

        if (! file_exists($cachePath)) {
            self::buildZip($cachePath, $attachmentIds);
        }

        if (! file_exists($cachePath)) {
            wp_die(
                esc_html__('Impossible de générer l’archive ZIP.', 'sage'),
                esc_html__('Téléchargement indisponible', 'sage'),
                ['response' => 500]
            );
        }

        self::streamFile($cachePath, $downloadName);
    }

    /**
     * @param  array<int, int>  $attachmentIds
     */
    private static function buildZip(string $cachePath, array $attachmentIds): void
    {
        $zip = new \ZipArchive();
        $opened = $zip->open($cachePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($opened !== true) {
            return;
        }

        $usedNames = [];

        foreach ($attachmentIds as $attachmentId) {
            $resolved = MediaAttachmentData::resolve($attachmentId);

            if ($resolved === null) {
                continue;
            }

            $path = get_attached_file($attachmentId);

            if (! is_string($path) || $path === '' || ! file_exists($path)) {
                continue;
            }

            $entryName = self::uniqueEntryName($resolved['filename'], $usedNames);
            $zip->addFile($path, $entryName);
        }

        $zip->close();
    }

    /**
     * @param  array<string, true>  $usedNames
     */
    private static function uniqueEntryName(string $filename, array &$usedNames): string
    {
        $base = sanitize_file_name($filename);

        if ($base === '') {
            $base = 'fichier';
        }

        if (! isset($usedNames[$base])) {
            $usedNames[$base] = true;

            return $base;
        }

        $pathInfo = pathinfo($base);
        $name = $pathInfo['filename'] ?? 'fichier';
        $extension = isset($pathInfo['extension']) ? '.'.$pathInfo['extension'] : '';
        $counter = 2;

        while (isset($usedNames[$name.'-'.$counter.$extension])) {
            $counter++;
        }

        $unique = $name.'-'.$counter.$extension;
        $usedNames[$unique] = true;

        return $unique;
    }

    private static function streamFile(string $path, string $downloadName): void
    {
        $safeName = sanitize_file_name($downloadName);

        if ($safeName === '') {
            $safeName = 'kit.zip';
        }

        if (! str_ends_with(strtolower($safeName), '.zip')) {
            $safeName .= '.zip';
        }

        nocache_headers();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.$safeName.'"');
        header('Content-Length: '.(string) filesize($path));
        header('X-Robots-Tag: noindex', true);

        readfile($path);
        exit;
    }

    public static function downloadNameForPost(int $postId, string $lang): string
    {
        $post = get_post($postId);
        $slug = $post instanceof \WP_Post ? $post->post_name : 'kit';

        if (! BlockLocales::isValid($lang)) {
            $lang = BlockLocales::default();
        }

        return 'kit-'.$slug.'-'.$lang;
    }

    /**
     * @return array<int, int>
     */
    public static function validatedAttachmentIds(int $postId, string $lang): array
    {
        if (! BlockLocales::isValid($lang)) {
            return [];
        }

        return KitData::attachmentIdsFromPost($postId, $lang);
    }
}
