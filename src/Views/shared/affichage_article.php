<?php
require_once __DIR__ . '/../../../src/config.php';
require_once __DIR__ . '/../../Controllers/QueryController.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Orange Cat Only</title>
</head>
<body>

<div class="articles">
    <?php foreach ($articles as $article): ?>
        <div class="article">

             <?= htmlspecialchars("@ " . $article['username']) ?> 

            <h2><?= htmlspecialchars($article['titre']) ?></h2>
            <p><?= nl2br(htmlspecialchars($article['description'])) ?></p>
            <small>
                Catégorie : <?= htmlspecialchars($article['categorie'] ?? 'Aucune') ?>
            </small>

            <?php if (isset($_SESSION['user_id']) && $article['user_id'] == $_SESSION['user_id']): ?>
                <div class="article-actions">
                    <form action="?page=user/article&action=delete" method="post">
                        <input type="hidden" name="article_id" value="<?= (int)$article['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="article-actions">
                <a href="?page=user/commentaire_form&id=<?= (int)$article['id'] ?>">Commenter</a>
            </div>
        </div>

        <div class="commentaires">
            <h3>Commentaires récents</h3>
            <?php if (!empty($article['commentaires'])): ?>
                <?php foreach ($article['commentaires'] as $commentaire): ?>
                    <div class="commentaire">
                        <?= htmlspecialchars("@ " . $commentaire['username']) ?>
                        <p><?= nl2br(htmlspecialchars($commentaire['description'])) ?></p>
                    </div>
                        <?php if (isset($_SESSION['user_id']) && $article['user_id'] == $_SESSION['user_id']): ?>

                            <form action="?page=user/commentaire&action=delete" method="post">
                                <input type="hidden" name="commentaire_id" value="<?= (int)$commentaire['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php endif; ?>

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

</body>
</html>
