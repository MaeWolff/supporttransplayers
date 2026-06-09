<?php

/**
 * Theme setup.
 */

namespace App;

use App\Support\HeroData;
use Illuminate\Support\Facades\Vite;

/**
 * Resolve a built asset URL from the Vite manifest.
 * Used in wp-admin where cross-origin dev server modules are blocked.
 */
function manifest_asset_url(string $entry): ?string
{
    $manifestPath = get_theme_file_path('public/build/manifest.json');

    if (! file_exists($manifestPath)) {
        return null;
    }

    $manifest = json_decode((string) file_get_contents($manifestPath), true);

    if (! is_array($manifest) || ! isset($manifest[$entry]['file'])) {
        return null;
    }

    return get_theme_file_uri('public/build/'.$manifest[$entry]['file']);
}

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    try {
        $css = Vite::content('resources/css/editor.css');
    } catch (\Throwable) {
        $css = null;
    }

    if (is_string($css) && $css !== '') {
        $settings['styles'][] = ['css' => $css];
    } else {
        $style = manifest_asset_url('resources/css/editor.css') ?? Vite::asset('resources/css/editor.css');
        $settings['styles'][] = [
            'css' => "@import url('{$style}')",
        ];
    }

    return $settings;
});

/**
 * Load block editor scripts from compiled assets (avoids Vite dev-server CORS in wp-admin).
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    $dependencies = [
        'wp-blocks',
        'wp-block-editor',
        'wp-components',
        'wp-element',
        'wp-i18n',
        'wp-server-side-render',
    ];

    try {
        $manifestDeps = json_decode(Vite::content('editor.deps.json'));

        if (is_array($manifestDeps)) {
            $dependencies = $manifestDeps;
        }
    } catch (\Throwable) {
        //
    }

    foreach ($dependencies as $dependency) {
        if (is_string($dependency) && ! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    $script = manifest_asset_url('resources/js/editor.js') ?? Vite::asset('resources/js/editor.js');

    wp_enqueue_script(
        'sage-editor',
        $script,
        $dependencies,
        null,
        true
    );

    add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
        if ($handle !== 'sage-editor') {
            return $tag;
        }

        if (str_contains($tag, 'type=')) {
            return $tag;
        }

        return str_replace('<script ', '<script type="module" ', $tag);
    }, 10, 3);
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Ensure Header block exists on front page and register blocks.
 *
 * @return void
 */
add_action('init', function () {
    HeroData::ensureBlockOnFrontPage();
});

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    load_textdomain('sage', get_template_directory().'/resources/lang/'.determine_locale().'.mo');

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => stp_pll__('Primary Navigation'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable the custom logo from Appearance > Customize > Site Identity.
     *
     * @link https://developer.wordpress.org/themes/functionality/custom-logo/
     */
    add_theme_support('custom-logo', [
        'width' => 156,
        'flex-width' => true,
        'flex-height' => true,
        'header-text' => ['site-title', 'site-description'],
    ]);

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => stp_pll__('Primary'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => stp_pll__('Footer'),
        'id' => 'sidebar-footer',
    ] + $config);
});
