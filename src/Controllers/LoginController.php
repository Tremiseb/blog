<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';

class LoginController {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function handleRequest(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $erreur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = login($this->pdo, $username, $password);

            if ($user) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: ' . BASE_URL . '/public/index.php?page=admin/accueil');
                } else {
                    header('Location: ' . BASE_URL . '/public/index.php?page=user/accueil');
                }
                exit;
            } else {
                $erreur = 'Identifiant ou mot de passe incorrect';
            }
        }

        require __DIR__ . '/../Views/shared/login.php';
    }
}
