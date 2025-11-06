<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

// Gestion de la recherche et du filtre
$query = $_GET['query'] ?? null;
$category = $_GET['category'] ?? null;

if ($query) {
    $posts = array_filter($posts, fn($p) => str_contains(strtolower($p['title']), strtolower($query)));
}

if ($category) {
    $posts = array_filter($posts, fn($p) => $p['category'] === $category);
}