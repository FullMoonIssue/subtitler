<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        'doctrine_annotation_spaces' => true,
        'doctrine_annotation_indentation' => true,
        'doctrine_annotation_braces' => true,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_summary' => false,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder([
        (new \PhpCsFixer\Finder())->exclude(['var', 'vendor']),
    ]);
