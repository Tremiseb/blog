<?php

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/Database/db.php';

require_once dirname(__DIR__) . '/Database/articleRepository.php';     // liste/pagination articles
require_once dirname(__DIR__) . '/Database/categorieRepository.php';   // CRUD catégories

class AdminController
{
    private PDO $pdo;
    private int $limit = 5; // pagination comme l'user

    public function __construct()
    {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(string $action = 'accueil'): void
    {
        // Sécurité (ton routes.php filtre déjà normalement)
        if (empty($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "Accès admin requis.";
            exit;
        }

        switch ($action) {
            case 'accueil':
            case 'categories': // alias : même écran
                $this->showAccueil();
                break;

            case 'categories/create':
                $this->createCategoryAction();
                break;

            case 'categories/delete':
                $this->deleteCategoryAction();
                break;

            default:
                http_response_code(404);
                echo "404 - Page admin non trouvée";
                exit;
        }
    }

    private function showAccueil(): void
    {
        // pagination articles
        $page   = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
        $limit  = $this->limit;
        $offset = ($page - 1) * $limit;

        $articles      = getArticlesLimit($this->pdo, $limit, $offset);
        $totalArticles = getArticlesCount($this->pdo);
        $totalPages    = (int)ceil($totalArticles / $limit);

        // catégories pour panneau admin
        $categories = cat_getAll($this->pdo);

        require dirname(__DIR__) . '/Views/admin/accueil_admin.php';
    }

    /** Action POST: création de catégorie */
    private function createCategoryAction(): void {
        $nom = $_POST['nom'] ?? '';
        cat_create($this->pdo, $nom);
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=admin/accueil');
        exit;
    }

    private function deleteCategoryAction(): void {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        cat_delete($this->pdo, $id, true); // supprime aussi les articles liés
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=admin/accueil');
        exit;
    }
}
