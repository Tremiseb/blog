<?php
require_once __DIR__ . '/../../../src/config.php';
require_once __DIR__ . '/../../Controllers/CommentaireController.php';
require_once __DIR__ . '/../../Database/commentaireRepository.php';

$commentaires = [];
if ($article) {
    $commentaires = getCommentairesByArticle($pdo, $article['id']);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Ajouter un commentaire - Orange Cat Only</title>
    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/commentaire/style.css" rel="stylesheet">
</head>
<body class="page-login">

<div class="article-preview">

    <p class="article-author"><?= htmlspecialchars("@ " . $article['username']) ?></p>

    <h2><?= htmlspecialchars($article['titre']) ?></h2>

    <p><?= nl2br(htmlspecialchars($article['description'])) ?></p>

    <small>Catégorie : <?= htmlspecialchars($article['categorie'] ?? 'Aucune') ?></small>

    <?php if (!empty($commentaires)): ?>
        <div class="commentaires">
            <h3>Commentaires</h3>
            <?php foreach ($commentaires as $com): ?>
                <div class="commentaire">
                    <p><strong>@<?= htmlspecialchars($com['username']) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($com['description'])) ?></p>

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $com['user_id']): ?>
                        <form action="?page=user/commentaire&action=delete" method="post">
                            <input type="hidden" name="commentaire_id" value="<?= (int)$com['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>


<div class="form-container">


    <h1>Ajouter un commentaire</h1>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?page=user/commentaire&action=add">
        <input type="hidden" name="article_id" value="<?= (int)($_GET['id'] ?? 0) ?>">
        <textarea class="input" name="description" placeholder="Votre commentaire..." rows="5" required></textarea>
        <button class="btn_connexion" type="submit">Envoyer</button>
    </form>
</div>

</body>
</html>
