<?php
require_once __DIR__ . '/../config.php';          
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = login($pdo, $email, $password);

    if ($user) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];


        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/accueil');
        } else {
            header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
        }
        exit;
    } else {
        $erreur = "L'email ou le mot de passe n'est pas valide";
    }
}
