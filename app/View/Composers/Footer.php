<?php

namespace App\View\Composers;

use App\Support\FooterData;
use Roots\Acorn\View\Composer;

class Footer extends Composer
{
    /**
     * @var array<int, string>
     */
    protected static $views = [
        'sections.footer',
        'components.burger-menu',
    ];

    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        return [
            'footer' => FooterData::forView(),
        ];
    }
}
