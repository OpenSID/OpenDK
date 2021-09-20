<?php

declare(strict_types=1);

$header = <<<'EOF'
@package	OpenDK
@author	opendesa
@license	https://opensource.org/licenses/MIT	MIT License
@link	https://github.com/OpenSID/OpenDK
@since	Version v21.08.01
@filesource
EOF;

$finder = PhpCsFixer\Finder::create()
    // ->in([
    //     __DIR__ . '/app',
    //     __DIR__ . '/helpers',
    //     __DIR__ . '/routes',
    //     // __DIR__ . '/database', // Timeout
    // ])
    ->in(__DIR__)
    ->exclude([
        'bootstrap',
        'database', // Timeout
        'public',
        'storage',
        'test',
        'vendor',
    ])
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        // 'header_comment' => ['header' => $header],
    ])
    ->setUsingCache(false)
    ->setLineEnding(PHP_EOL)
    ->setFinder($finder);