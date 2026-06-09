<?php

/**
 * Server-side render for the Supporters block.
 *
 * @var array<string, mixed> $attributes
 * @var string               $content
 * @var WP_Block             $block
 */

$supporters = \App\Support\SupportersData::fromAttributes($attributes);

echo view('sections.supporters', ['supporters' => $supporters])->render();
