<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@PSR2'                             => true,
        'array_syntax'                      => ['syntax' => 'short'],
        'ordered_imports'                   => ['sortAlgorithm' => 'alpha'],
        'no_unused_imports'                 => true,
        'binary_operator_spaces'            => ['default' => 'align_single_space_minimal'],
        'trailing_comma_in_multiline_array' => true,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(false);

return $config;
