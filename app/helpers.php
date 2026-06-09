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
