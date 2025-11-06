<?php

$limit = 5; 
$page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$offset = ($page - 1) * $limit;

$searchName ="Rechercher" ;
$searchCategory ="Filter" ;

$articles = [];     
$query = $_POST['query'] ?? '';
$category = $_POST['category'] ?? '';

if (!empty($query)) {

    $id_user = getUserIdByUsername($pdo, $query);
    if ($id_user !== null) {
        $articles = getArticlesByUserLimit($pdo, $id_user, $limit, $offset);
        $totalArticles = countArticlesByUser($pdo, $id_user);
        $searchName ="X" ;


    } else {
        $articles = []; 
    }

} elseif (!empty($category)) {

    $categoryId = (int)$category;
    $articles = getArticlesByCategorieLimit($pdo, $categoryId, $limit, $offset);
    $totalArticles = countArticlesByCategorie($pdo, $categoryId);
    $searchCategory ="X" ;

} else {

    $articles = getArticlesLimit($pdo, $limit, $offset);
    $totalArticles = getArticlesCount($pdo);

}

$totalPages = (int)ceil($totalArticles / $limit);

foreach ($articles as &$article) {
    $article['commentaires'] = getCommentairesByArticleLimit($pdo, $article['id'], 2);
}
unset($article); // libère la référence
?>
