<?php

/**
 * Server-side render for the Header block.
 *
 * @var array<string, mixed> $attributes
 * @var string               $content
 * @var WP_Block             $block
 */

$hero = \App\Support\HeroData::fromAttributes($attributes);

echo view('sections.hero', ['hero' => $hero])->render();
