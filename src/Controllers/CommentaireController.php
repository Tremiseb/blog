<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/commentaireRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$user_id = $_SESSION['user_id'] ?? null;
$action = $_GET['action'] ?? null;

switch ($action) {

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
            $commentaire_id = $_POST['commentaire_id'] ?? null;

            if ($commentaire_id) {
                if (supprimerCommentaire($pdo, $commentaire_id, $user_id)) {

                    if ($_SESSION['role'] === 'admin') {
                        $path = "admin/accueil";
                    } else {
                        $path = "user/accueil";
                    }

                    header('Location: ' . BASE_URL . '/public/index.php?page='. $path);
                } else {
                    $erreur = "Impossible de supprimer le commentaire";
                }
            }
        }
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
            $article_id = $_POST['article_id'] ?? null;
            $description = trim($_POST['description'] ?? '');

            if ($article_id && $description) {
                if (addCommentaire($pdo, $article_id, $user_id, $description)) {

                    if ($_SESSION['role'] === 'admin') {
                        $path = "admin/accueil";
                    } else {
                        $path = "user/accueil";
                    }

                    header('Location: ' . BASE_URL . '/public/index.php?page='. $path);
                } else {
                    $erreur = "Impossible d'ajouter le commentaire";
                }
            }
        }
        break;
}
