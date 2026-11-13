<?php

require __DIR__ . "/vendor/autoload.php";
include __DIR__ . '/src/Framework/Database.php';

use Framework\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new Database(
    $_ENV['DB_DRIVER'] ?? 'mysql',
    [
        'host'   => $_ENV['DB_HOST']   ?? 'localhost',
        'port'   => $_ENV['DB_PORT']   ?? 3306,
        'dbname' => $_ENV['DB_NAME']   ?? 'budget-manager-mvc'
    ],
    $_ENV['DB_USER'] ?? 'root',
    $_ENV['DB_PASS'] ?? ''
);

$sqlFile = file_get_contents(__DIR__ . "/database.sql");

$db->query($sqlFile);
