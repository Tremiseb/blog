<?php 
require_once __DIR__ . '/../../config.php'; 
require_once __DIR__ . '/../../Database/db.php';
require_once __DIR__ . '/../../Database/articleRepository.php';
require_once __DIR__ . '/../../Database/commentaireRepository.php';

$pageTitle = "Accueil";
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

    <link href="<?= BASE_URL ?>/public/assets/shared/footer/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/footer/position.css" rel="stylesheet">

</head>

<body>

<?php

    $boutonCreerArticle = $boutonCreerArticle ?? "Créer un article";
    $creerArticle = $creerArticle ?? BASE_URL . "/public/index.php?page=user/creation-article";

    $bouton = $bouton ?? "Déconnexion";
    $redirection = $redirection ?? BASE_URL . "/public/index.php?page=login";

    require_once __DIR__ . '/../shared/header.php';
?>




<div class="articles">
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <h2><?= htmlspecialchars($article['titre']) ?></h2>
            <p><?= nl2br(htmlspecialchars($article['description'])) ?></p>
            <small>
                Par <?= htmlspecialchars($article['username']) ?> 
                | Catégorie : <?= htmlspecialchars($article['categorie'] ?? 'Aucune') ?>
            </small>

            <?php if (isset($_SESSION['user_id']) && $article['user_id'] == $_SESSION['user_id']): ?>
                <div class="article-actions">
                    <form action="?page=article/supprimer" method="post" class="form-supprimer">
                        <input type="hidden" name="article_id" value="<?= (int)$article['id'] ?>">
                        <button type="submit" class="btn-supprimer">Supprimer</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="article-actions">
                <a href="?page=user/commentaire_form&id=<?= (int)$article['id'] ?>" class="btn-commenter">
                    Commenter
                </a>
            </div>
        </div>
        <div class="commentaires">
            <h3>Commentaires récents</h3>

            <?php if (!empty($article['commentaires'])): ?>
                <?php foreach ($article['commentaires'] as $commentaire): ?>
                    <div class="commentaire">
                        <p><?= nl2br(htmlspecialchars($commentaire['description'])) ?></p>
                        <small>Par <?= htmlspecialchars($commentaire['username']) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun commentaire pour cet article.</p>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>
</div>



<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=user/accueil&page_num=<?= $page - 1 ?>">Précédent</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=user/accueil&page_num=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=user/accueil&page_num=<?= $page + 1 ?>">Suivant</a>
    <?php endif; ?>
</div>

<?php
    require_once __DIR__ . '/../shared/footer.php';

?>


</body>
</html>
