<?php

// database and other settings

return [
    'db' => [
        'host' => getenv('PHP_HOST'),
        'dbname' => getenv('PHP_DSN'),
        'user' => getenv('PHP_USER'),
        'pass' => getenv('PHP_PASSWD')
    ]
];