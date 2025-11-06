<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $role     = $_POST['role'] ?? '';

    if (!$username || !$password || !$email || !$role) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {

        if (emailExists($pdo, $email)) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $userCreated = CreerUtilisateur($pdo, $email, $password, $username, $role);
            if ($userCreated) {
                header('Location: ' . BASE_URL . '/public/index.php?page=login');
                exit;
            } else {
                $erreur = "Impossible de créer le compte.";
            }
        }
    }
}

