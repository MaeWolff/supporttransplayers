<?php

namespace App\Support;

class BlockLocales
{
    /** @var list<string> */
    public const LANGUAGES = ['fr', 'en', 'es'];

    public static function current(): string
    {
        if (function_exists('pll_current_language')) {
            $lang = pll_current_language('slug');

            if (is_string($lang) && self::isValid($lang)) {
                return $lang;
            }
        }

        return self::default();
    }

    public static function default(): string
    {
        if (function_exists('pll_default_language')) {
            $lang = pll_default_language('slug');

            if (is_string($lang) && self::isValid($lang)) {
                return $lang;
            }
        }

        return 'fr';
    }

    public static function isValid(string $lang): bool
    {
        return in_array($lang, self::LANGUAGES, true);
    }

    public static function isDefaultLanguage(int $postId): bool
    {
        if (! function_exists('pll_get_post_language')) {
            return true;
        }

        $lang = pll_get_post_language($postId, 'slug');

        return is_string($lang) && $lang === self::default();
    }

    /**
     * @return array<int, int>
     */
    public static function translationPostIds(int $postId): array
    {
        if (! function_exists('pll_get_post_translations')) {
            return [];
        }

        $translations = pll_get_post_translations($postId);

        if (! is_array($translations)) {
            return [];
        }

        $ids = [];

        foreach ($translations as $lang => $translationId) {
            if ($lang === self::default()) {
                continue;
            }

            $translationId = (int) $translationId;

            if ($translationId > 0 && $translationId !== $postId) {
                $ids[] = $translationId;
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * @param  array<string, mixed>  $locales
     * @return array<string, mixed>
     */
    public static function normalize(array $locales): array
    {
        $normalized = [];

        foreach (self::LANGUAGES as $lang) {
            $normalized[$lang] = is_array($locales[$lang] ?? null) ? $locales[$lang] : [];
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function resolve(array $attributes, ?string $locale = null): array
    {
        $locale = $locale && self::isValid($locale) ? $locale : self::current();

        if (isset($attributes['locales']) && is_array($attributes['locales'])) {
            $locales = self::normalize($attributes['locales']);

            if (self::localesAreEmpty($locales) && self::hasLegacyAttributes($attributes)) {
                $locales = self::migrateLegacyLocales($attributes);
            }
        } else {
            $locales = self::migrateLegacyLocales($attributes);
        }

        $data = is_array($locales[$locale] ?? null) ? $locales[$locale] : [];

        if ($data === [] && $locale !== self::default()) {
            $fallback = is_array($locales[self::default()] ?? null) ? $locales[self::default()] : [];

            if ($fallback !== []) {
                $data = $fallback;
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, array<string, mixed>>
     */
    public static function migrateLegacyLocales(array $attributes): array
    {
        $locales = self::emptyLocales();

        if (isset($attributes['title']) || isset($attributes['description']) || isset($attributes['zipLabel']) || isset($attributes['items'])) {
            $locales['fr'] = [
                'title' => (string) ($attributes['title'] ?? ''),
                'description' => (string) ($attributes['description'] ?? ''),
                'zipLabel' => (string) ($attributes['zipLabel'] ?? ''),
                'items' => is_array($attributes['items'] ?? null) ? $attributes['items'] : [],
            ];

            return $locales;
        }

        if (isset($attributes['sectionTitle']) || isset($attributes['items'])) {
            $locales['fr'] = [
                'sectionTitle' => (string) ($attributes['sectionTitle'] ?? ''),
                'items' => is_array($attributes['items'] ?? null) ? $attributes['items'] : [],
            ];

            return $locales;
        }

        if (is_array($attributes['locales'] ?? null) && $attributes['locales'] !== []) {
            return self::normalize($attributes['locales']);
        }

        return $locales;
    }

    /**
     * @param  array<string, array<string, mixed>>  $locales
     */
    public static function localesAreEmpty(array $locales): bool
    {
        foreach (self::LANGUAGES as $lang) {
            if (($locales[$lang] ?? []) !== []) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function hasLegacyAttributes(array $attributes): bool
    {
        return isset($attributes['title'])
            || isset($attributes['description'])
            || isset($attributes['zipLabel'])
            || isset($attributes['sectionTitle'])
            || isset($attributes['items']);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function emptyLocales(): array
    {
        $locales = [];

        foreach (self::LANGUAGES as $lang) {
            $locales[$lang] = [];
        }

        return $locales;
    }
}
