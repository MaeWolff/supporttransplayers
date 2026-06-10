<?php

namespace App\Support;

class NewsletterData
{
    public const BLOCK_NAME = 'stp/newsletter';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $buttonColor = $attributes['buttonColor'] ?? 'pink';

        return [
            'buttonColor' => in_array($buttonColor, ['pink', 'blue', 'beige'], true)
                ? $buttonColor
                : 'pink',
        ];
    }
}
