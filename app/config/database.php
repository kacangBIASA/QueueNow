<?php
// app/config/database.php
// PDO MySQL (Azure MySQL / MySQL biasa) - ambil dari .env

return [
    'driver'  => Env::get('DB_DRIVER', 'mysql'),
    'host'    => Env::get('DB_HOST', '127.0.0.1'),
    'port'    => (int) Env::get('DB_PORT', '3306'),
    'name'    => Env::get('DB_DATABASE', 'queuenow'),
    'user'    => Env::get('DB_USERNAME', 'root'),
    'pass'    => Env::get('DB_PASSWORD', ''),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),

    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
