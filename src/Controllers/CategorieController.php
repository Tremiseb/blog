<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/categorieRepository.php';
require_once __DIR__ . '/../Database/articleRepository.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pdo     = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$user_id = $_SESSION['user_id'] ?? null;
$role    = $_SESSION['role'] ?? null;
$action  = $_GET['action'] ?? null;


$redirectBack = BASE_URL . '/public/index.php?page=admin/accueil';

// Sécurité
if (!$user_id || $role !== 'admin') {
    http_response_code(403);
    exit("Accès réservé à l'admin.");
}

switch ($action) {

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            if (!createCategorie($pdo, $nom)) {
                $_SESSION['error'] = "Catégorie invalide ou déjà existante.";
            }
        }
        header("Location: $redirectBack");
        exit;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $hard = isset($_POST['hard']) && (int)$_POST['hard'] === 1;

            if (!supprimerCategorie($pdo, $id, $hard)) {
                $_SESSION['error'] = "Impossible de supprimer la catégorie.";
            }
        }
        header("Location: $redirectBack");
        exit;

    default:
        http_response_code(404);
        echo "Action inconnue";
        exit;
}
