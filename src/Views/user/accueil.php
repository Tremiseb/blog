<?php 
require_once __DIR__ . '/../../config.php'; 
require_once __DIR__ . '/../../Database/db.php';
require_once __DIR__ . '/../../Database/articleRepository.php';
require_once __DIR__ . '/../../Database/categorieRepository.php';

require_once __DIR__ . '/../../Database/commentaireRepository.php';
$query = $_GET['query'] ?? null;

$pageTitle = "Orange Cat Only";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?php echo($pageTitle) ?></title>

    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet"> 
    <link href="<?= BASE_URL ?>/public/assets/shared/header/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/header/position.css" rel="stylesheet">

    <link href="<?= BASE_URL ?>/public/assets/user/style.css" rel="stylesheet">

    <link href="<?= BASE_URL ?>/public/assets/shared/footer/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/footer/position.css" rel="stylesheet">

</head>

<body>

    <?php
        $boutonCreerArticle = $boutonCreerArticle ?? "Créer un article";
        $creerArticle = $creerArticle ?? BASE_URL . "/public/index.php?page=user/creation-article";

        $bouton = $bouton ?? "Déconnexion";
        $redirection = $redirection ?? BASE_URL . "/public/index.php?page=logout";

        require_once __DIR__ . '/../shared/header.php';

        require_once __DIR__ . '/../shared/affichage_article.php';

        require_once __DIR__ . '/../shared/footer.php';

    ?>
