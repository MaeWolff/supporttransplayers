<?php

/**
 * Server-side render for the Kit block.
 *
 * @var array<string, mixed> $attributes
 * @var string               $content
 * @var WP_Block             $block
 */

$kit = \App\Support\KitData::fromAttributes($attributes, (int) get_the_ID());

echo view('sections.kit', ['kit' => $kit])->render();
