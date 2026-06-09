<?php

use App\Support\LegalTextData;

/**
 * Server-side render for the Legal Text block.
 *
 * @var array<string, mixed> $attributes
 * @var string $content
 * @var WP_Block $block
 */
$legalText = LegalTextData::fromAttributes($attributes);

echo view('sections.legal-text', ['legalText' => $legalText])->render();
