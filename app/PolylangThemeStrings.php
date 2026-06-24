<?php

namespace App;

/**
 * Register theme strings from app/ for Polylang String translations.
 *
 * @see https://github.com/roots/sage/issues/1875#issuecomment-380076482
 */
add_action('init', function () {
    if (! function_exists('pll_register_string')) {
        return;
    }

    $strings = [
        'Continued',
        'Primary Navigation',
        'Footer Navigation',
        'Primary',
        'Navigation',
        'Mentions légales',
        'Suivez-nous',
        'Langue',
        'Language switcher',
        'Iels nous soutiennent',
        'Logo de soutien',
        'Latest Posts',
        'Search Results for %s',
        'Not Found',
        'Pages:',
        'Sorry, but the page you are trying to view does not exist.',
        'Sorry, no results were found.',
        'Skip to content',
        'Comments are closed.',
        'By',
        '&larr; Older comments',
        'Newer comments &rarr;',
        'One',
        'Restez à l\'affût des dernières news',
        'S\'inscrire',
        'En savoir plus',
        'Campagne en cours de construction',
        'Notre site arrive bientôt. Merci de votre patience.',
    ];

    foreach ($strings as $string) {
        pll_register_string($string, $string, 'sage', false);
    }

    pll_register_string('Search for:', 'Search for:', 'sage', false);
    pll_register_string('Search …', 'Search &hellip;', 'sage', false);
    pll_register_string('Search submit', 'Search', 'sage', false);
    pll_register_string('newsletter placeholder', 'Votre adresse e-mail', 'sage', false);
    pll_register_string('newsletter label', 'Adresse e-mail', 'sage', false);
}, 20);
