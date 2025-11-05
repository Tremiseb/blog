<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';

require_once __DIR__ . '/../Database/articleRepository.php';   // pour la liste/pagination articles
require_once __DIR__ . '/../Database/categorieRepository.php'; // catégories séparées

class AdminController {

    private PDO $pdo;
    private int $limit = 5; // même pagination que UserController

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(string $action = 'accueil'): void {
        // Sécurité (ton routes.php filtre déjà normalement)
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Accès admin requis.";
            exit;
        }

        switch ($action) {
            case 'accueil':
            case 'categories': // alias, même écran
                $this->showAccueil();
                break;

            case 'categories/create':
                $this->createCategory();
                break;

            case 'categories/delete':
                $this->deleteCategory();
                break;

            // si tu ajoutes l’édition :
            // case 'categories/update':
            //     $this->updateCategory();
            //     break;

            default:
                http_response_code(404);
                echo "404 - Page admin non trouvée";
                exit;
        }
    }

    private function showAccueil(): void {
        // pagination articles – identique à UserController
        $page   = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
        $limit  = $this->limit;
        $offset = ($page - 1) * $limit;

        $articles      = getArticlesLimit($this->pdo, $limit, $offset);
        $totalArticles = getArticlesCount($this->pdo);
        $totalPages    = (int)ceil($totalArticles / $limit);

        // catégories via le repo dédié
        $categories = cat_getAll($this->pdo);

        require __DIR__ . '/../Views/admin/accueil_admin.php';
    }

    private function createCategory(): void {
        $nom = $_POST['nom'] ?? '';
        cat_create($this->pdo, $nom);

        header('Location: ' . BASE_URL . 'public/index.php?page=admin/accueil');
        exit;
    }

    private function deleteCategory(): void {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        cat_delete($this->pdo, $id);

        header('Location: ' . BASE_URL . 'public/index.php?page=admin/accueil');
        exit;
    }

    // Optionnel si tu ajoutes un formulaire d’édition
    // private function updateCategory(): void {
    //     $id  = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    //     $nom = $_POST['nom'] ?? '';
    //     cat_update($this->pdo, $id, $nom);
    //     header('Location: ' . BASE_URL . 'public/index.php?page=admin/accueil');
    //     exit;
    // }
}
