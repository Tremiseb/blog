<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

class UserController {

    private PDO $pdo;
    private int $limit = 5; 

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(string $action = 'accueil'): void {

        switch ($action) {
            case 'accueil':
                $this->showAccueil();
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

}
