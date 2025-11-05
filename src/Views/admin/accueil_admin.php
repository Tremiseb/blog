<?php 
require_once __DIR__ . '/../../config.php'; 
require_once __DIR__ . '/../../Database/db.php';

$pageTitle = "Admin — Accueil";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet"> 
    <link href="<?= BASE_URL ?>/public/assets/shared/header/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/header/position.css" rel="stylesheet">

    
    <link href="<?= BASE_URL ?>/public/assets/user/style.css" rel="stylesheet">
    
    <link href="<?= BASE_URL ?>/public/assets/admin/style.css" rel="stylesheet">

    <link href="<?= BASE_URL ?>/public/assets/shared/footer/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/footer/position.css" rel="stylesheet">
</head>
<body>

<?php
    // mêmes variables que la page user (pour éviter les warnings dans header.php)
    $nav = $nav ?? ['Accueil', 'Avis', 'Nos réalisations', 'Contact']; 
    $boutonCreerArticle = $boutonCreerArticle ?? null; // pas obligatoire en admin
    $creerArticle = $creerArticle ?? null;
    $bouton = $bouton ?? "Déconnexion";
    $redirection = $redirection ?? BASE_URL . "/public/index.php?page=logout";

    require_once __DIR__ . '/../shared/header.php';
?>


<section class="admin-panel">
    <h1>Gestion des catégories</h1>

    <form class="admin-add-cat" method="POST"
        action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?page=admin/categories/create">
        <input type="text" name="nom" placeholder="Nouvelle catégorie" required>
        <button type="submit" class="ajouter">Ajouter</button>
    </form>

    <?php if (!empty($categories)): ?>
        <ul class="admin-cat-list">
            <?php foreach ($categories as $cat): ?>
                <li>
                    <span><?= htmlspecialchars($cat['nom']) ?></span>
                    <form method="POST"
                        action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?page=admin/categories/delete"
                        onsubmit="return confirm('Supprimer la catégorie « <?= htmlspecialchars($cat['nom']) ?> » et ses articles ?');">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <button type="submit" class="supprimer">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune catégorie pour le moment.</p>
    <?php endif; ?>
</section>




<div class="articles">
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <h2><?= htmlspecialchars($article['titre']) ?></h2>
            <p><?= nl2br(htmlspecialchars($article['description'])) ?></p>
            <small>
                Par <?= htmlspecialchars($article['username']) ?> |
                Catégorie : <?= htmlspecialchars($article['categorie'] ?? 'Aucune') ?>
            </small>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=admin/accueil&page_num=<?= $page - 1 ?>">Précédent</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=admin/accueil&page_num=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>>
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=admin/accueil&page_num=<?= $page + 1 ?>">Suivant</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
