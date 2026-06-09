<?php

/**
 * Register theme blocks.
 */

namespace App;

add_action('init', function () {
    register_block_type(get_theme_file_path('resources/blocks/hero'));
    register_block_type(get_theme_file_path('resources/blocks/supporters'));
});
