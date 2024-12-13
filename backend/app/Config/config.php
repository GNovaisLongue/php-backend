<?php

// database and other settings

return [
    'db_type'   => $_ENV['DB_TYPE'],
    'host'      => $_ENV['MYSQL_HOST'],
    'dbname'    => $_ENV['MYSQL_DATABASE'],
    'user'      => $_ENV['MYSQL_USER'],
    'pass'      => $_ENV['MYSQL_PASSWORD'],
    'charset'   => $_ENV['MYSQL_DB_CHARSET'],
];