<?php

namespace App\Support;

class HeroData
{
    public const META_KEY = '_stp_hero';

    public const BLOCK_NAME = 'stp/hero';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $buttons = [];

        $buttonFields = [
            [
                'label' => $attributes['button1Label'] ?? '',
                'url' => $attributes['button1Url'] ?? '',
                'color' => $attributes['button1Color'] ?? 'pink',
            ],
            [
                'label' => $attributes['button2Label'] ?? '',
                'url' => $attributes['button2Url'] ?? '',
                'color' => $attributes['button2Color'] ?? 'blue',
            ],
        ];

        foreach ($buttonFields as $button) {
            if (! filled($button['label']) || ! filled($button['url'])) {
                continue;
            }

            $buttons[] = [
                'label' => $button['label'],
                'url' => $button['url'],
                'color' => in_array($button['color'], ['pink', 'blue', 'beige'], true) ? $button['color'] : 'pink',
            ];
        }

        return [
            'title' => $attributes['title'] ?? '',
            'description' => $attributes['description'] ?? '',
            'buttons' => $buttons,
        ];
    }

    /**
     * @param  array<string, mixed>  $hero
     * @return array<string, mixed>
     */
    public static function toBlockAttributes(array $hero): array
    {
        $attributes = [
            'title' => $hero['title'] ?? '',
            'description' => $hero['description'] ?? '',
            'button1Label' => '',
            'button1Url' => '',
            'button1Color' => 'pink',
            'button2Label' => '',
            'button2Url' => '',
            'button2Color' => 'blue',
        ];

        $buttons = $hero['buttons'] ?? [];

        if (isset($buttons[0]) && is_array($buttons[0])) {
            $attributes['button1Label'] = $buttons[0]['label'] ?? '';
            $attributes['button1Url'] = $buttons[0]['url'] ?? '';
            $attributes['button1Color'] = in_array($buttons[0]['color'] ?? '', ['pink', 'blue'], true)
                ? $buttons[0]['color']
                : 'pink';
        }

        if (isset($buttons[1]) && is_array($buttons[1])) {
            $attributes['button2Label'] = $buttons[1]['label'] ?? '';
            $attributes['button2Url'] = $buttons[1]['url'] ?? '';
            $attributes['button2Color'] = in_array($buttons[1]['color'] ?? '', ['pink', 'blue'], true)
                ? $buttons[1]['color']
                : 'blue';
        }

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function serializeBlock(array $attributes): string
    {
        return serialize_blocks([
            [
                'blockName' => self::BLOCK_NAME,
                'attrs' => $attributes,
                'innerBlocks' => [],
                'innerHTML' => '',
                'innerContent' => [],
            ],
        ]);
    }

    public static function contentHasBlock(string $content): bool
    {
        if (! function_exists('parse_blocks')) {
            return str_contains($content, 'wp:stp/hero');
        }

        foreach (parse_blocks($content) as $block) {
            if (($block['blockName'] ?? null) === self::BLOCK_NAME) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'title' => 'Support Trans Players',
            'description' => '',
            'buttons' => [],
        ];
    }

    /**
     * Ensure the front page contains a Header block for Gutenberg selection.
     */
    public static function ensureBlockOnFrontPage(): void
    {
        if (get_option('stp_hero_block_ready')) {
            return;
        }

        $frontPageId = (int) get_option('page_on_front');

        if (! $frontPageId) {
            return;
        }

        $post = get_post($frontPageId);

        if (! $post instanceof \WP_Post) {
            return;
        }

        if (self::contentHasBlock($post->post_content)) {
            update_option('stp_hero_block_ready', true);

            return;
        }

        $meta = get_post_meta($frontPageId, self::META_KEY, true);
        $hero = is_array($meta) && filled($meta['title'] ?? null) ? $meta : self::defaults();
        $block = self::serializeBlock(self::toBlockAttributes($hero));

        wp_update_post([
            'ID' => $frontPageId,
            'post_content' => $block.$post->post_content,
        ]);

        delete_post_meta($frontPageId, self::META_KEY);
        delete_option('stp_hero_migrated');
        update_option('stp_hero_block_ready', true);
    }
}
