<?php

namespace App\Support;

class ResourcesData
{
    public const BLOCK_NAME = 'stp/resources';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $localeData = BlockLocales::resolve($attributes, BlockLocales::current());

        return [
            'sectionTitle' => trim((string) ($localeData['sectionTitle'] ?? '')),
            'items' => self::normalizeItems(is_array($localeData['items'] ?? null) ? $localeData['items'] : []),
        ];
    }

    /**
     * @param  array<int, mixed>  $items
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($item): bool => is_array($item))
            ->map(function (array $item): ?array {
                $attachmentId = (int) ($item['attachmentId'] ?? 0);
                $resolved = MediaAttachmentData::resolve($attachmentId);

                if ($resolved === null) {
                    return null;
                }

                $title = trim((string) ($item['title'] ?? $item['label'] ?? ''));
                $description = trim((string) ($item['description'] ?? ''));

                return [
                    ...$resolved,
                    'title' => $title !== '' ? $title : $resolved['filename'],
                    'description' => $description,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
