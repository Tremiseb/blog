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

// Si on clique sur le bouton creer article 

$error = '';
$categories = getCategories($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $categorie_id = $_POST['categorie_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$titre || !$description || !$user_id) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $created = createArticle($pdo, $titre, $description, $user_id, $categorie_id);
        if ($created) {
            header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
            exit;
        } else {
            $error = "Impossible de créer l'article.";
        }
    }
}
