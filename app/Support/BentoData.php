<?php

namespace App\Support;

class BentoData
{
    public const BLOCK_NAME = 'stp/bento';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $items = self::normalizeItems($attributes['items'] ?? []);

        if ($items === []) {
            $items = self::defaults();
        }

        return [
            'sectionTitle' => filled($attributes['sectionTitle'] ?? null)
                ? (string) $attributes['sectionTitle']
                : 'Pourquoi ce combat ?',
            'items' => $items,
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
                $title = trim((string) ($item['title'] ?? ''));
                $body = trim((string) ($item['body'] ?? ''));

                if ($title === '' && $body === '') {
                    return null;
                }

                $url = trim((string) ($item['url'] ?? ''));
                $linkLabel = trim((string) ($item['linkLabel'] ?? ''));

                if ($url !== '' && $linkLabel === '') {
                    $linkLabel = stp_pll__('En savoir plus');
                }

                $color = $item['color'] ?? 'pink';
                $size = $item['size'] ?? 'medium';

                return [
                    'title' => $title,
                    'body' => $body,
                    'url' => $url,
                    'linkLabel' => $linkLabel,
                    'color' => in_array($color, ['pink', 'blue', 'beige', 'white'], true)
                        ? $color
                        : 'pink',
                    'size' => in_array($size, ['large', 'medium', 'small'], true)
                        ? $size
                        : 'medium',
                    'external' => self::isExternalUrl($url),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaults(): array
    {
        return [
            [
                'title' => 'Les JO 2028 remettent en cause nos corps',
                'body' => 'Le CIO prévoit de réintroduire des tests de féminité. Une régression pour les personnes trans et intersexes dans le sport.',
                'url' => '',
                'linkLabel' => '',
                'color' => 'pink',
                'size' => 'large',
                'external' => false,
            ],
            [
                'title' => 'Protéger la loi éthique',
                'body' => 'En France, ces tests sont interdits. Cette protection doit tenir face aux normes internationales.',
                'url' => '',
                'linkLabel' => '',
                'color' => 'blue',
                'size' => 'medium',
                'external' => false,
            ],
            [
                'title' => 'Agir ensemble',
                'body' => 'Pétitions, mobilisations, outils pour les clubs et les fédérations — retrouvez les actions en cours.',
                'url' => '',
                'linkLabel' => '',
                'color' => 'beige',
                'size' => 'large',
                'external' => false,
            ],
            [
                'title' => 'Le sport pour toutes et tous',
                'body' => 'Contre la transphobie institutionnelle, pour un sport accueillant.',
                'url' => '',
                'linkLabel' => '',
                'color' => 'white',
                'size' => 'small',
                'external' => false,
            ],
        ];
    }

    public static function isExternalUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }
}
