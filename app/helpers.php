<?php

/**
 * Theme translation helpers.
 */

if (! function_exists('stp_pll_register_string')) {
    /**
     * Register a theme string with Polylang once.
     */
    function stp_pll_register_string(string $name, string $text): void
    {
        if (! function_exists('pll_register_string')) {
            return;
        }

        static $registered = [];

        if (isset($registered[$name])) {
            return;
        }

        pll_register_string($name, $text, 'sage', false);
        $registered[$name] = true;
    }
}

if (! function_exists('stp_pll__')) {
    /**
     * Translate a string via Polylang when available, otherwise WordPress i18n.
     */
    function stp_pll__(string $text): string
    {
        stp_pll_register_string($text, $text);

        if (function_exists('pll__')) {
            return pll__($text);
        }

        return __($text, 'sage');
    }
}

if (! function_exists('stp_font_uri')) {
    /**
     * Resolve a theme font URL from resources/fonts/.
     */
    function stp_font_uri(string $filename): string
    {
        return \App\font_uri($filename);
    }
}

if (! function_exists('stp_nav_menu_tree')) {
    /**
     * Build a hierarchical tree of nav menu items for a theme location.
     *
     * @return array<int, \WP_Post>
     */
    function stp_nav_menu_tree(string $location): array
    {
        $locations = get_nav_menu_locations();

        if (! isset($locations[$location])) {
            return [];
        }

        $items = wp_get_nav_menu_items((int) $locations[$location]);

        if (! is_array($items) || $items === []) {
            return [];
        }

        $indexed = [];

        foreach ($items as $item) {
            $item->children = [];
            $indexed[$item->ID] = $item;
        }

        $tree = [];

        foreach ($items as $item) {
            $parentId = (int) $item->menu_item_parent;

            if ($parentId !== 0 && isset($indexed[$parentId])) {
                $indexed[$parentId]->children[] = $item;
            } else {
                $tree[] = $item;
            }
        }

        return $tree;
    }
}

if (! function_exists('stp_pll_x')) {
    /**
     * Translate a string with context via Polylang when available.
     */
    function stp_pll_x(string $text, string $context): string
    {
        $name = "context:{$context}|{$text}";

        stp_pll_register_string($name, $text);

        if (function_exists('pll__')) {
            return pll__($text);
        }

        return _x($text, $context, 'sage');
    }
}
