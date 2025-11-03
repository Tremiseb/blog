<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/LoginController.php';
require_once __DIR__ . '/Controllers/RegisterController.php';
require_once __DIR__ . '/Controllers/LogoutController.php';

$page = $_GET['page'] ?? 'home';

$pagesPubliques = ['login', 'register', 'logout', 'home'];

if (!in_array($page, $pagesPubliques) && empty($_SESSION['user'])) {

    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}


if (str_starts_with($page, 'admin')) {

    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Faut etre connecté";
        exit;
    }

    $subPage = str_replace('admin/', '', $page);

    $adminController = new AdminController();
    $adminController->handleRequest($subPage);
    exit;
}


if (str_starts_with($page, 'user')) {

    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'user') {
        http_response_code(403);
        echo "Faut etre connecté";
        exit;
    }

    $subPage = str_replace('user/', '', $page);

    $adminController = new UserController();
    $adminController->handleRequest($subPage);
    exit;
}


switch ($page) {
    case 'login':
        $loginController = new LoginController();
        $loginController->handleRequest();
        break;

    case 'register':
        $registerController = new RegisterController();
        $registerController->handleRequest();
        break;

    case 'home':
        ShowHomeController(); 
        break;

    case 'logout':
        $controller = new LogoutController();
        $controller->handleRequest();
        break;

    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
    
    }
