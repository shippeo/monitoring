<?php

$config = new PhpCsFixer\Config();
$config
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src', 'tests'])
    )
    ->registerCustomFixers(new PedroTroller\CS\Fixer\Fixers())
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules(
        [
            '@DoctrineAnnotation' => true,
            'date_time_immutable' => true,
            'declare_strict_types' => true,
            'linebreak_after_opening_tag' => true,
            'list_syntax' => [
                'syntax' => 'short'
            ],
            'method_argument_space' => [
                'on_multiline' => 'ensure_fully_multiline',
            ],
            'native_function_invocation' => ['strict' => false],
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'static',
            ],
            'yoda_style' => false,
            'PedroTroller/line_break_between_method_arguments' => [
                'max-length' => 120,
                'max-args' => 3,
            ],
            'PedroTroller/phpspec' => true,
        ]
    )
;

return $config;
