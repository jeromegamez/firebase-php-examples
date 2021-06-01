<?php

$finder = PhpCsFixer\Finder::create()
    ->in(['src']);

return (new PhpCsFixer\Config)
    ->setUsingCache(true)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'header_comment' => ['header' => ''],
        'ordered_imports' => true,
        'phpdoc_align' => false,
        'phpdoc_order' => true,
        'yoda_style' => false,
    ])
    ->setFinder($finder);
