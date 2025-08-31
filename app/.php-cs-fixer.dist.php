<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests'])
    ->name('*.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        // 'declare_strict_types' => true, // usunięte - koliduje z PHPCS
        'no_unused_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,
        'blank_line_before_statement' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => false,
        'concat_space' => ['spacing' => 'one'],
        // 'header_comment' => [...]       // usunięte - Fixer wrzucał header po declare
    ]);
