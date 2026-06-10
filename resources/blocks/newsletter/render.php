<?php

use App\Support\NewsletterData;

/**
 * Server-side render for the Newsletter block.
 *
 * @var array<string, mixed> $attributes
 * @var string $content
 * @var WP_Block $block
 */
$newsletter = NewsletterData::fromAttributes($attributes);

echo view('sections.newsletter', ['newsletter' => $newsletter])->render();
