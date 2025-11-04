<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/LoginController.php';
require_once __DIR__ . '/Controllers/RegisterController.php';
require_once __DIR__ . '/Controllers/LogoutController.php';

require_once __DIR__ . '/Controllers/AdminController.php';


require_once __DIR__ . '/Controllers/UserController.php';


$page = $_GET['page'] ?? 'home';

$pagesPubliques = ['login', 'register', 'logout', 'home'];

if (!in_array($page, $pagesPubliques) && empty($_SESSION['email'])) {

   header('Location: ' . BASE_URL . '/public/index.php');
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
        $logoutController = new LogoutController();
        $logoutController->handleRequest();
        break;

    case 'admin/accueil':

        if (empty($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Faut être connecté en admin";
            exit;
        }

        ShowAdminHomeController();
        break;

    case 'user/accueil':

        if (empty($_SESSION['email']) || $_SESSION['role'] !== 'user') {
            http_response_code(403);
            echo "Faut être connecté en user";
            exit;
        }

        $userController = new UserController();
        $userController->handleRequest('accueil');
        break;

    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
