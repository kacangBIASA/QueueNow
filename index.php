<?php
// index.php (root)
declare(strict_types=1);

session_start();

// Autoload sederhana (tanpa composer)
require __DIR__ . '/app/core/Autoload.php';

/**
 * =========================
 * Load .env (WAJIB sebelum require config)
 * =========================
 * Pastikan kamu bikin file: app/core/Env.php (yang loader .env)
 */
require_once __DIR__ . '/app/core/Env.php';
Env::load(__DIR__ . '/.env');

// Load config
$appConfig = require __DIR__ . '/app/config/app.php';
date_default_timezone_set($appConfig['timezone'] ?? 'Asia/Jakarta');

// Register config ke helper global
$GLOBALS['config'] = [
  'app'      => $appConfig,
  'database' => require __DIR__ . '/app/config/database.php',
  'midtrans' => require __DIR__ . '/app/config/midtrans.php',
  'plans'    => require __DIR__ . '/app/config/plans.php',
  'google'   => require __DIR__ . '/app/config/google.php',
];

// Core
$router = new Router();

// Routes
require __DIR__ . '/app/routes/web.php';

// Jalankan router
$router->dispatch();
