<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$user_id = $_SESSION['user_id'] ?? null;
$action = $_GET['action'] ?? null;
$error = '';
$path;

switch ($action) {

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $categorie_id = $_POST['categorie_id'] ?? null;

            if (!$titre || !$description) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                if (createArticle($pdo, $titre, $description, $user_id, $categorie_id)) {

                    if ($_SESSION['role'] === 'admin') {
                        $path = "admin/accueil";
                    } else {
                        $path = "user/accueil";
                    }
                    
                    header('Location: ' . BASE_URL . '/public/index.php?page=' . $path);
                    exit;
                } else {
                    $error = "Impossible de créer l'article.";
                }
            }
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
            $article_id = $_POST['article_id'] ?? null;

            if ($article_id) {
                if (supprimerArticle($pdo, $article_id, $user_id)) {

                    if ($_SESSION['role'] === 'admin') {
                        $path = "admin/accueil";
                    } else {
                        $path = "user/accueil";
                    }

                    header('Location: ' . BASE_URL . '/public/index.php?page=' . $path);
                    exit;
                } else {
                    $error = "Impossible de supprimer l'article.";
                }
            }
        }
        break;
}
