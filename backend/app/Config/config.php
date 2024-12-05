<?php

// database and other settings

return [
    'host'      => $_ENV['DB_HOST'],
    'dbname'    => $_ENV['DB_DSN'],
    'user'      => $_ENV['DB_USER'],
    'pass'      => $_ENV['DB_PASSWD'],
    'charset'   => $_ENV['DB_CHARSET'],
];