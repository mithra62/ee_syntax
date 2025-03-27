<?php

use Mithra62\Syntax\Services\FilterService;

require_once 'vendor/autoload.php';

return [
    'name'              => 'Syntax',
    'description'       => 'Syntax highlighting that uses the GeSHi syntax highlighter class.',
    'version'           => '1.0.0',
    'author'            => 'mithra62',
    'author_url'        => 'fdsa',
    'namespace'         => 'Mithra62\Syntax',
    'settings_exist'    => false,
    'services' => [
        'FilterService' => function ($addon) {
            return new FilterService();
        },
    ]
];
