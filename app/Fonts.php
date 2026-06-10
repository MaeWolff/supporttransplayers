<?php

/**
 * Theme font loading.
 *
 * Fonts are served from resources/fonts/ with stable URLs so wp-admin
 * and production do not depend on Vite-hashed assets in public/build/.
 */

namespace App;

/**
 * Resolve a theme font URL.
 */
function font_uri(string $filename): string
{
    return get_theme_file_uri('resources/fonts/'.$filename);
}

/**
 * @font-face rules for theme fonts.
 */
function font_faces_css(): string
{
    $instrument = esc_url(font_uri('InstrumentSans-Regular.woff2'));
    $bebas = esc_url(font_uri('BebasNeue-Regular.woff2'));

    return <<<CSS
@font-face {
  font-family: 'Instrument Sans';
  src: url('{$instrument}') format('woff2');
  font-style: normal;
  font-weight: 400;
  font-display: swap;
}

@font-face {
  font-family: 'Bebas Neue';
  src: url('{$bebas}') format('woff2');
  font-style: normal;
  font-weight: 400;
  font-display: swap;
}
CSS;
}

/**
 * Enqueue font faces for the front end and wp-admin.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    wp_register_style('stp-fonts', false);
    wp_enqueue_style('stp-fonts');
    wp_add_inline_style('stp-fonts', font_faces_css());
}, 1);

add_action('admin_enqueue_scripts', function () {
    wp_register_style('stp-fonts', false);
    wp_enqueue_style('stp-fonts');
    wp_add_inline_style('stp-fonts', font_faces_css());
}, 1);
