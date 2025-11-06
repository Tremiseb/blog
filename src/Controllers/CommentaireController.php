<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/commentaireRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = $_POST['article_id'] ?? null;
    $description = trim($_POST['description'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;

    if ($article_id && $description && $user_id) {

        if (addCommentaire($pdo, $article_id, $user_id, $description)) {
            header('Location: ?page=user/accueil');
            exit;
        } else {

            echo "Impossible d'ajouter le commentaire.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
