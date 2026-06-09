<?php

namespace App\Support;

class SupportersData
{
    public const BLOCK_NAME = 'stp/supporters';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $supporters = self::normalizeSupporters($attributes['supporters'] ?? []);

        if ($supporters === []) {
            $supporters = self::placeholders();
        }

        return [
            'title' => filled($attributes['title'] ?? null)
                ? (string) $attributes['title']
                : __('They support us', 'sage'),
            'supporters' => $supporters,
        ];
    }

    /**
     * @param  array<int, mixed>  $items
     * @return array<int, array{name: string, url: string, logo: string}>
     */
    public static function normalizeSupporters(array $items): array
    {
        return collect($items)
            ->filter(fn ($item): bool => is_array($item))
            ->map(function (array $item): ?array {
                $logoId = (int) ($item['logoId'] ?? 0);

                if (! $logoId) {
                    return null;
                }

                $name = trim((string) ($item['name'] ?? ''));
                $url = trim((string) ($item['url'] ?? ''));

                return [
                    'name' => $name,
                    'url' => $url,
                    'logo' => wp_get_attachment_image($logoId, 'full', false, [
                        'class' => 'max-h-16 w-auto max-w-full object-contain',
                        'alt' => $name !== '' ? $name : __('Logo de soutien', 'sage'),
                        'decoding' => 'async',
                        'loading' => 'lazy',
                    ]),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{name: string, url: string, logo: string}>
     */
    public static function placeholders(): array
    {
        $items = [
            ['name' => 'Outsport', 'url' => 'https://example.com'],
            ['name' => 'Athlete Ally', 'url' => 'https://example.com'],
            ['name' => 'Pride House', 'url' => 'https://example.com'],
            ['name' => 'Collectif Trans', 'url' => 'https://example.com'],
            ['name' => 'Fair Play', 'url' => 'https://example.com'],
            ['name' => 'Sport Inclusive', 'url' => 'https://example.com'],
        ];

        return collect($items)->map(function (array $item): array {
            $name = $item['name'];

            return [
                'name' => $name,
                'url' => $item['url'],
                'logo' => sprintf(
                    '<span class="text-center font-display text-lg uppercase leading-none text-neutral-black md:text-xl">%s</span>',
                    e($name)
                ),
            ];
        })->all();
    }
}
