<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/categorieRepository.php';

$pdo     = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$user_id = $_SESSION['user_id'] ?? null;
$role    = $_SESSION['role'] ?? 'admin';
$action  = $_GET['action'] ?? null;
$error   = '';


switch ($action) {

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            if (!createCategorie($pdo, $nom)) {
                $error = "Impossible de créer la catégorie (vide ou déjà existante).";
            }
            header('Location: ' . $redirectBack);
            exit;
        }
        else {
                    $error = "Impossible de créer la catégorie.";
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            
            $hard = isset($_POST['hard']) && (int)$_POST['hard'] === 1;

            if (!supprimerCategorie($pdo, $id, $hard)) {
                $error = "Impossible de supprimer la catégorie.";
            }
            header('Location: ' . $redirectBack);
            exit;
        }
        else {
                    $error = "Impossible de supprimer la catégorie.";
        }
        break;
}
