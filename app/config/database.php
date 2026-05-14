<?php
// app/config/database.php

$sslOptions = [];
// Jika tidak berjalan di localhost (misalnya di Render), kita gunakan sertifikat Aiven
if (getenv('DB_HOST') && getenv('DB_HOST') !== '127.0.0.1' && getenv('DB_HOST') !== 'localhost') {
    $sslOptions = [
        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/aiven-ca.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
}

return [
    'driver'  => getenv('DB_DRIVER') ?: 'mysql',
    'host'    => getenv('DB_HOST') ?: '127.0.0.1',
    'port'    => (int)(getenv('DB_PORT') ?: 3306),
    'name'    => getenv('DB_DATABASE') ?: 'queuenow',
    'user'    => getenv('DB_USERNAME') ?: 'root',
    'pass'    => getenv('DB_PASSWORD') ?: '',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',

    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ] + $sslOptions,
];
