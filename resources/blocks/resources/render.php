<?php

/**
 * Server-side render for the Resources block.
 *
 * @var array<string, mixed> $attributes
 * @var string               $content
 * @var WP_Block             $block
 */

$resources = \App\Support\ResourcesData::fromAttributes($attributes);

echo view('sections.resources', ['resources' => $resources])->render();
