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
    <link href="<?= BASE_URL ?>/public/assets/admin/style.css" rel="stylesheet">

</head>

<body>

    <?php
        $boutonCreerArticle = $boutonCreerArticle ?? "Créer un article";
        $creerArticle = $creerArticle ?? BASE_URL . "/public/index.php?page=user/creation-article";

        $bouton = $bouton ?? "Déconnexion";
        $redirection = $redirection ?? BASE_URL . "/public/index.php?page=logout";
    ?>   

    
    
    <?php
        require_once __DIR__ . '/../shared/header.php';
    ?>
    
        <section class="admin-panel">
            <h1>Gestion des catégories</h1>

            <form class="admin-add-cat" method="POST"
                action="<?= BASE_URL ?>/public/index.php?page=admin/categorie&action=create">
                <input type="text" name="nom" placeholder="Nouvelle catégorie" required>
                <button type="submit" class="ajouter">Ajouter</button>
            </form>

            <?php if (!empty($categories)): ?>
                <ul class="admin-cat-list">
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <span><?= htmlspecialchars($cat['nom']) ?></span>
                            <form method="POST"
                                action="<?= BASE_URL ?>/public/index.php?page=admin/categorie&action=delete"
                                onsubmit="return confirm('Supprimer la catégorie « <?= htmlspecialchars($cat['nom']) ?> » ET tous les articles associés ? Cette action est définitive.');">

                                <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                                <input type="hidden" name="hard" value="1">   

                                <button type="submit" class="supprimer">Supprimer</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune catégorie pour le moment.</p>
            <?php endif; ?>
        </section>
    <?php
        require_once __DIR__ . '/../shared/affichage_article.php';

        require_once __DIR__ . '/../shared/footer.php';

    ?>