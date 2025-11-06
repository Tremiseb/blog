<?php
require_once __DIR__ . '/../src/config.php';


$page = $_GET['page'] ?? 'login';

$pagesPubliques = ['login', 'register', 'logout', 'home'];

if (!in_array($page, $pagesPubliques) && empty($_SESSION['email'])) {

   header('Location: ' . BASE_URL . '/public/index.php');
   exit;
}


switch ($page) {
    case 'login':
        require_once __DIR__ . '/Views/shared/login.php';
        break;

    case 'register':
        require_once __DIR__ . '/Views/shared/register.php';
        break;

    case 'user/accueil':
        require_once __DIR__ . '/Views/user/accueil.php';
        break;

    case 'user/accueil':
        require_once __DIR__ . '/Views/user/accueil.php';
        break;

    case 'user/create':
        require_once __DIR__ . '/Views/shared/create_article.php';
        break;


    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
