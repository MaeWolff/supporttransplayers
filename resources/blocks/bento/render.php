<?php

use App\Support\BentoData;

/**
 * Server-side render for the Bento block.
 *
 * @var array<string, mixed> $attributes
 * @var string $content
 * @var WP_Block $block
 */
$bento = BentoData::fromAttributes($attributes);

echo view('sections.bento', ['bento' => $bento])->render();
