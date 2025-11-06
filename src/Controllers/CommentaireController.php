<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/commentaireRepository.php';
require_once __DIR__ . '/../Database/articleRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$user_id = $_SESSION['user_id'] ?? null;
$action = $_GET['action'] ?? null;
$error = null;

$article_id = (int)($_GET['id'] ?? 0);
$article = null;
if ($article_id > 0) {
    $article = getArticleById($pdo, $article_id);
    if (!$article) {
        $error = "Article introuvable";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {

    switch ($action) {

        case 'add':
            $description = trim($_POST['description'] ?? '');
            $article_id_post = (int)($_POST['article_id'] ?? 0);

            if ($article_id_post && $description) {
                $success = addCommentaire($pdo, $article_id_post, $user_id, $description);
                if ($success) {

                    $path = ($_SESSION['role'] === 'admin') ? "admin/accueil" : "user/accueil";
                    header('Location: ' . BASE_URL . '/public/index.php?page=' . $path);
                    exit;
                } else {
                    $error = "Impossible d'ajouter le commentaire";
                }
            } else {
                $error = "Tous les champs sont obligatoires";
            }
            break;

        case 'delete':
            $commentaire_id = (int)($_POST['commentaire_id'] ?? 0);
            if ($commentaire_id) {
                $success = supprimerCommentaire($pdo, $commentaire_id, $user_id);
                if ($success) {
                    $path = ($_SESSION['role'] === 'admin') ? "admin/accueil" : "user/accueil";
                    header('Location: ' . BASE_URL . '/public/index.php?page=' . $path);
                    exit;
                } else {
                    $error = "Impossible de supprimer le commentaire";
                }
            } else {
                $error = "Commentaire introuvable";
            }
            break;

        default:
            $error = "Action non reconnue";
            break;
    }
}

