<?php require_once __DIR__ . '/../../config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Orange Cat Only') ?></title>

    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet"> 

    <link href="<?= BASE_URL ?>/public/assets/shared/header/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/header/position.css" rel="stylesheet">


</head>

<body>

    <?php
        $nav = $nav ?? ['Accueil', 'Avis', 'Nos réalisations', 'Contact']; 
        $bouton = $bouton ?? "Déconnexion";
        $redirection = $redirection ?? BASE_URL . "/public/index.php?page=login";

        require_once __DIR__ . '/../shared/header.php';
        
    ?>
</body>
</html>
