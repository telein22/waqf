<?php

use System\Core\Config;

$database = Config::get('Database');

$database->set(array(
     'host' => $_ENV['MYSQL_HOST'],
     'user' => $_ENV['MYSQL_USER'],
     'password' => $_ENV['MYSQL_PASSWORD'],
     'database' => $_ENV['MYSQL_DATABASE'],
     'port' => $_ENV['MYSQL_PORT'],

    // Connection options
    'options' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci', time_zone = '" . date("P") ."'",
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    ]
));
