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

        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = login($this->pdo, $email, $password);

            if ($user) {
                $_SESSION['email'] = $user['email'];
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
