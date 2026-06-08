<?php

/**
 * Theme customizer settings.
 */

namespace App;

add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    $wp_customize->add_section('hero', [
        'title' => __('Hero', 'sage'),
        'priority' => 30,
    ]);

    $fields = [
        'hero_title' => [
            'label' => __('Titre', 'sage'),
            'type' => 'text',
            'default' => 'Support Trans Players',
        ],
        'hero_description' => [
            'label' => __('Description', 'sage'),
            'type' => 'textarea',
            'default' => 'Une association dédiée à la visibilité et au soutien des personnes trans dans le sport.',
        ],
        'hero_button_1_label' => [
            'label' => __('Bouton 1 — Label', 'sage'),
            'type' => 'text',
            'default' => 'Nous soutenir',
        ],
        'hero_button_1_url' => [
            'label' => __('Bouton 1 — Lien', 'sage'),
            'type' => 'url',
            'default' => '#',
        ],
        'hero_button_2_label' => [
            'label' => __('Bouton 2 — Label', 'sage'),
            'type' => 'text',
            'default' => 'En savoir plus',
        ],
        'hero_button_2_url' => [
            'label' => __('Bouton 2 — Lien', 'sage'),
            'type' => 'url',
            'default' => '#',
        ],
    ];

    foreach ($fields as $id => $field) {
        $sanitize = match ($field['type']) {
            'url' => 'esc_url_raw',
            'textarea' => 'sanitize_textarea_field',
            default => 'sanitize_text_field',
        };

        $wp_customize->add_setting($id, [
            'default' => $field['default'],
            'sanitize_callback' => $sanitize,
        ]);

        $wp_customize->add_control($id, [
            'label' => $field['label'],
            'section' => 'hero',
            'type' => $field['type'],
        ]);
    }
});
