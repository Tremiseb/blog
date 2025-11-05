<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/commentaireRepository.php';

class CommentaireController {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handleRequest(string $action = 'createForm'): void {
        switch ($action) {
            case 'createForm':
                $this->createForm();
                break;

            case 'store':
                $this->store();
                break;

            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
                exit;
        }
    }

    private function createForm(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        $articleId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$articleId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
            exit;
        }

        $pageTitle = "Ajouter un commentaire";
        require __DIR__ . '/../Views/user/commentaire_form.php';
    }

    private function store(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = (int)($_POST['article_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            if ($articleId && $description) {
                $commentaireRepo = new CommentaireRepository($this->pdo);
                $commentaireRepo->addCommentaire($articleId, $_SESSION['user_id'], $description);
                header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
                exit;

            }

            header('Location: ' . BASE_URL . '/public/index.php?page=user/article&id=' . $articleId);
            exit;
        }

        http_response_code(400);
        echo "Requête invalide.";
    }

}
