<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/articleRepository.php';

class AdminController {

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
        // Pagination
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        // Récupérer les articles
        $articles = getArticlesLimit($this->pdo, $this->limit, $offset);

        // Passer à la vue
        require __DIR__ . '/../Views/user/accueil.php';
    }
}
