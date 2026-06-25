<?php

namespace App\Support;

class KitData
{
    public const BLOCK_NAME = 'stp/kit';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes, int $postId = 0): array
    {
        $locale = BlockLocales::current();
        $localeData = BlockLocales::resolve($attributes, $locale);
        $items = self::normalizeItems(is_array($localeData['items'] ?? null) ? $localeData['items'] : []);
        $postId = $postId > 0 ? $postId : (int) get_the_ID();

        $zipLabel = trim((string) ($localeData['zipLabel'] ?? ''));

        return [
            'title' => trim((string) ($localeData['title'] ?? '')),
            'description' => trim((string) ($localeData['description'] ?? '')),
            'zipLabel' => $zipLabel !== '' ? $zipLabel : stp_pll__('Télécharger le kit complet'),
            'items' => $items,
            'postId' => $postId,
            'locale' => $locale,
            'zipUrl' => $postId > 0 && $items !== []
                ? home_url('stp-download/kit/'.$postId.'/'.$locale.'/')
                : null,
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

                $alt = trim((string) ($item['alt'] ?? ''));

                if ($alt !== '') {
                    $resolved['alt'] = $alt;
                } else {
                    $resolved['alt'] = $resolved['filename'];
                }

                return $resolved;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, int>
     */
    public static function attachmentIdsFromPost(int $postId, ?string $lang = null): array
    {
        $post = get_post($postId);

        if (! $post instanceof \WP_Post || $post->post_type !== 'page' || $post->post_status !== 'publish') {
            return [];
        }

        $lang = $lang && BlockLocales::isValid($lang) ? $lang : BlockLocales::current();
        $ids = [];

        foreach (parse_blocks($post->post_content) as $block) {
            if (($block['blockName'] ?? '') !== self::BLOCK_NAME) {
                continue;
            }

            $attrs = is_array($block['attrs'] ?? null) ? $block['attrs'] : [];
            $localeData = BlockLocales::resolve($attrs, $lang);

            foreach ($localeData['items'] ?? [] as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $attachmentId = (int) ($item['attachmentId'] ?? 0);

                if ($attachmentId > 0) {
                    $ids[] = $attachmentId;
                }
            }
        }

        return array_values(array_unique($ids));
    }
}
