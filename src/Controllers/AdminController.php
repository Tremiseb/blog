<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

require_once __DIR__ . '/../Database/categorieRepository.php';   // CRUD catégories

class AdminController
{
    private PDO $pdo;
    private int $limit = 5; 

    public function __construct()
    {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(string $action = 'accueil'): void
    {
         
        if (empty($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "Accès admin requis.";
            exit;
        }

        switch ($action) {
            case 'accueil':
            case 'categories': 
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
        
        $page   = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
        $limit  = $this->limit;
        $offset = ($page - 1) * $limit;

        $articles      = getArticlesLimit($this->pdo, $limit, $offset);
        $totalArticles = getArticlesCount($this->pdo);
        $totalPages    = (int)ceil($totalArticles / $limit);

        $commentaireRepo = new CommentaireRepository($this->pdo);

        foreach ($articles as &$article) {
        $article['commentaires'] = $commentaireRepo->getCommentairesByArticleLimit($article['id'], 2);

        
        $categories = cat_getAll($this->pdo);

        require __DIR__ . '/../Views/admin/accueil_admin.php';
    }


    private function deleteArticle(): void {
    
        $adminId = $_SESSION['user_id'] ?? null;
        if (!$adminId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
            $articleId = (int)$_POST['article_id'];

            
            if (supprimerArticle($this->pdo, $articleId, $adminId, true)) {
                $_SESSION['message'] = "Article supprimé (admin).";
            } else {
                $_SESSION['message'] = "Erreur : suppression échouée.";
            }
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/accueil');
            exit;
        }

        http_response_code(400);
        echo "Requête invalide.";
    }

    private function createArticle(): void {

        $adminId = $_SESSION['user_id'] ?? null;
        if (!$adminId) {
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
            } elseif (creerArticle($this->pdo, $adminId, $titre, $description, $categorieId)) {
                $_SESSION['message'] = "Article créé (admin).";
                header('Location: ' . BASE_URL . '/public/index.php?page=admin/accueil');
                exit;
            } else {
                $error = "Erreur lors de la création de l'article.";
            }
        }

        $articleData = ['titre' => '', 'description' => '', 'categorie_id' => ''];
        $isEdit = false;
        $actionUrl = BASE_URL . '/public/index.php?page=admin/creation';
        require __DIR__ . '/../Views/user/create_article.php'; // réutilisation de la même vue
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


}