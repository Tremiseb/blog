<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

class UserController {

    private PDO $pdo;
    private int $limit = 5;

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handleRequest(string $action = 'accueil'): void {
        switch ($action) {
            case 'accueil':
                $this->showAccueil();
                break;

            case 'supprimer':
                $this->deleteArticle();
                break;

            case 'creation':
                $this->createArticle();
                break;

            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
                exit;
        }
    }

    private function showAccueil(): void {
        $page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
        $limit = $this->limit;
        $offset = ($page - 1) * $limit;

        $articles = getArticlesLimit($this->pdo, $limit, $offset);
        $totalArticles = getArticlesCount($this->pdo);
        $totalPages = (int)ceil($totalArticles / $limit);

        require __DIR__ . '/../Views/user/accueil_user.php';
    }

    private function deleteArticle(): void {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
            $articleId = (int)$_POST['article_id'];

            if (supprimerArticle($this->pdo, $articleId, $userId)) {
                $_SESSION['message'] = "L'article a été supprimé avec succès.";
            } else {
                $_SESSION['message'] = "Erreur : impossible de supprimer cet article.";
            }

            header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
            exit;
        }

        http_response_code(400);
        echo "Requête invalide.";
    }

    private function createArticle(): void {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        $error = '';
        $categories = getCategories($this->pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $categorieId = !empty($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null;

            if (!$titre || !$description) {
                $error = "Veuillez remplir tous les champs.";
            } elseif (creerArticle($this->pdo, $userId, $titre, $description, $categorieId)) {
                $_SESSION['message'] = "L'article a été créé avec succès.";
                header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
                exit;
            } else {
                $error = "Erreur lors de la création de l'article.";
            }
        }

        $articleData = ['titre' => '', 'description' => '', 'categorie_id' => ''];
        $isEdit = false;
        $actionUrl = BASE_URL . '/public/index.php?page=user/creation';
        require __DIR__ . '/../Views/user/create_article.php';
    }

}
