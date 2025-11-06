<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';
require_once __DIR__ . '/../Database/articleRepository.php'; // inclure tes fonctions articles
require_once __DIR__ . '/../Database/commentaireRepository.php'; // si nécessaire

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$limit = 5;
$page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$offset = ($page - 1) * $limit;

// Valeurs par défaut pour la vue
$searchName = "Rechercher";
$searchCategory = 0;
$articles = [];
$totalArticles = 0;

// Récupération des valeurs POST
$action = $_POST['action'] ?? '';
$query = trim($_POST['query'] ?? '');
$category = $_POST['category'] ?? '';

switch ($action) {
    case 'search':
        if ($query !== "") {
            $id_user = getUserIdByUsername($pdo, $query);
            if ($id_user !== null) {
                $articles = getArticlesByUserLimit($pdo, $id_user, $limit, $offset);
                $totalArticles = countArticlesByUser($pdo, $id_user);

            } else {
                $articles = [];
                $totalArticles = 0;
                $searchName = "Aucun utilisateur trouvé";
            }
        }
        break;

    case 'filter':
        if (!empty($category)) {
            $categoryId = (int)$category;
            $articles = getArticlesByCategorieLimit($pdo, $categoryId, $limit, $offset);
            $totalArticles = countArticlesByCategorie($pdo, $categoryId);
            $searchCategory = 1;
        }
        break;

    case 'reset':
        // Réinitialiser le filtre
        $category = null;
        $articles = getArticlesLimit($pdo, $limit, $offset);
        $totalArticles = getArticlesCount($pdo);
        $searchCategory = 0;
        break;

    default:
        // Aucun filtre ni recherche
        $articles = getArticlesLimit($pdo, $limit, $offset);
        $totalArticles = getArticlesCount($pdo);
        $searchCategory = 0;
        break;
}

// Calcul du nombre total de pages pour la pagination
$totalPages = (int)ceil($totalArticles / $limit);

// Récupération des commentaires pour chaque article (limité à 2)
foreach ($articles as &$article) {
    $article['commentaires'] = getCommentairesByArticleLimit($pdo, $article['id'], 2);
}
unset($article); // libère la référence

?>
