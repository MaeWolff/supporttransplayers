<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Hero extends Composer
{
    /**
     * @var array<int, string>
     */
    protected static $views = [
        'sections.hero',
        'front-page',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function with(): array
    {
        return [
            'hero' => $this->heroData(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function heroData(): array
    {
        return [
            'title' => get_theme_mod('hero_title', 'Support Trans Players'),
            'description' => get_theme_mod('hero_description', ''),
            'buttons' => [
                [
                    'label' => get_theme_mod('hero_button_1_label', ''),
                    'url' => get_theme_mod('hero_button_1_url', ''),
                    'color' => 'pink',
                ],
                [
                    'label' => get_theme_mod('hero_button_2_label', ''),
                    'url' => get_theme_mod('hero_button_2_url', ''),
                    'color' => 'blue',
                ],
            ],
        ];
    }
}
