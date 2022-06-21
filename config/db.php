<?php

declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."../../");
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])->notEmpty();

if (!function_exists('env')) {
  function env(string $key, string $default) : string
  {
    return  strval($_ENV[$key] ?? $default);
  } 
}

return [
  'host' => env('DB_HOST', '127.0.0.1'),
  'port' => env('DB_PORT', '3306'),
  'database' => env('DB_DATABASE', ''),
  'username' => env('DB_USERNAME', ''),
  'password' => env('DB_PASSWORD', ''),
  'charset' => 'utf8mb4',
];
