<?php

namespace App\Support;

use League\CommonMark\GithubFlavoredMarkdownConverter;

class LegalTextData
{
    public const BLOCK_NAME = 'stp/legal-text';

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function fromAttributes(array $attributes): array
    {
        $content = trim((string) ($attributes['content'] ?? ''));

        if ($content === '') {
            return ['html' => ''];
        }

        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = $converter->convert($content)->getContent();

        return [
            'html' => wp_kses_post($html),
        ];
    }
}
