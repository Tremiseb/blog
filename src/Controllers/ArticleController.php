<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

class ArticleController {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';
        $categories = getCategories($this->pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $categorie_id = $_POST['categorie_id'] ?? null;
            $user_id = $_SESSION['user_id'] ?? null;

            if (!$titre || !$description || !$user_id) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $created = createArticle($this->pdo, $titre, $description, $user_id, $categorie_id);
                if ($created) {
                    header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
                    exit;
                } else {
                    $error = "Impossible de créer l'article.";
                }
            }
        }

        require __DIR__ . '/../Views/user/create_article.php';
    }
}
