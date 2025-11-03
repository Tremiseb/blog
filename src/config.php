<?php
session_start();
ini_set('display_errors', 1); // en prod 0
ini_set('display_startup_errors', 1); // en prod 0
error_reporting(E_ALL);

$config = parse_ini_file(__DIR__ . '/../config.ini', true);

define('DB_HOST', $config['database']['host'] ?? 'localhost');
define('DB_NAME', $config['database']['name'] ?? 'mydatabase');
define('DB_USER', $config['database']['user'] ?? 'root');
define('DB_PASS', $config['database']['pass'] ?? '');
define('BASE_URL', $config['app']['base_url'] ?? '/');

?>


