<?php

namespace App\Sync;

use App\Support\BlockLocales;
use App\Support\KitData;
use App\Support\ResourcesData;

class TranslatedBlockSync
{
    /** @var list<string> */
    private const SYNC_BLOCKS = [
        KitData::BLOCK_NAME,
        ResourcesData::BLOCK_NAME,
    ];

    /** @var array<int, true> */
    private static array $syncing = [];

    public static function register(): void
    {
        add_action('save_post_page', [self::class, 'handleSave'], 99, 3);
    }

    public static function handleSave(int $postId, \WP_Post $post, bool $update): void
    {
        unset($update);

        if (wp_is_post_autosave($postId) || wp_is_post_revision($postId)) {
            return;
        }

        if ($post->post_status === 'auto-draft' || isset(self::$syncing[$postId])) {
            return;
        }

        if (! BlockLocales::isDefaultLanguage($postId)) {
            return;
        }

        $translationIds = BlockLocales::translationPostIds($postId);

        if ($translationIds === []) {
            return;
        }

        $sourceBlocks = parse_blocks($post->post_content);
        $sourceSyncBlocks = self::collectSyncBlocks($sourceBlocks);

        if ($sourceSyncBlocks === []) {
            return;
        }

        foreach ($translationIds as $translationId) {
            self::syncToTranslation($postId, $translationId, $sourceSyncBlocks);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<string, array<int, array<string, mixed>>>
     */
    private static function collectSyncBlocks(array $blocks): array
    {
        $collected = [];

        foreach (self::SYNC_BLOCKS as $blockName) {
            $collected[$blockName] = [];
        }

        foreach ($blocks as $block) {
            $name = $block['blockName'] ?? '';

            if (! in_array($name, self::SYNC_BLOCKS, true)) {
                continue;
            }

            $collected[$name][] = $block;
        }

        return array_filter($collected, fn (array $items): bool => $items !== []);
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $sourceSyncBlocks
     */
    private static function syncToTranslation(int $sourceId, int $translationId, array $sourceSyncBlocks): void
    {
        $translation = get_post($translationId);

        if (! $translation instanceof \WP_Post) {
            return;
        }

        self::$syncing[$translationId] = true;

        $blocks = parse_blocks($translation->post_content);
        $merged = self::mergeBlocks($blocks, $sourceSyncBlocks);
        $content = serialize_blocks($merged);

        if ($content === $translation->post_content) {
            unset(self::$syncing[$translationId]);

            return;
        }

        wp_update_post([
            'ID' => $translationId,
            'post_content' => $content,
        ]);

        unset(self::$syncing[$translationId]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @param  array<string, array<int, array<string, mixed>>>  $sourceSyncBlocks
     * @return array<int, array<string, mixed>>
     */
    private static function mergeBlocks(array $blocks, array $sourceSyncBlocks): array
    {
        $indexes = [];

        foreach (self::SYNC_BLOCKS as $blockName) {
            $indexes[$blockName] = 0;
        }

        $merged = [];

        foreach ($blocks as $block) {
            $name = $block['blockName'] ?? '';

            if (in_array($name, self::SYNC_BLOCKS, true)) {
                $index = $indexes[$name] ?? 0;
                $sourceBlock = $sourceSyncBlocks[$name][$index] ?? null;

                if (is_array($sourceBlock)) {
                    $merged[] = [
                        'blockName' => $name,
                        'attrs' => is_array($sourceBlock['attrs'] ?? null) ? $sourceBlock['attrs'] : [],
                        'innerBlocks' => [],
                        'innerHTML' => '',
                        'innerContent' => [],
                    ];
                } else {
                    $merged[] = $block;
                }

                $indexes[$name] = $index + 1;

                continue;
            }

            $merged[] = $block;
        }

        foreach (self::SYNC_BLOCKS as $blockName) {
            $sourceBlocks = $sourceSyncBlocks[$blockName] ?? [];
            $index = $indexes[$blockName] ?? 0;

            while ($index < count($sourceBlocks)) {
                $sourceBlock = $sourceBlocks[$index];
                $merged[] = [
                    'blockName' => $blockName,
                    'attrs' => is_array($sourceBlock['attrs'] ?? null) ? $sourceBlock['attrs'] : [],
                    'innerBlocks' => [],
                    'innerHTML' => '',
                    'innerContent' => [],
                ];
                $index++;
            }
        }

        return $merged;
    }
}

TranslatedBlockSync::register();
