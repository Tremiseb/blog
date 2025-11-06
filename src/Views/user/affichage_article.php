<?php

// --------------------
// Pagination
// --------------------
$limit = 5; 
$page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$offset = ($page - 1) * $limit;

// --------------------
// Récupération des articles + commentaires limités
// --------------------
$articles = getArticlesLimit($pdo, $limit, $offset);
$totalArticles = getArticlesCount($pdo);
$totalPages = (int)ceil($totalArticles / $limit);

foreach ($articles as &$article) {
    $article['commentaires'] = getCommentairesByArticleLimit($pdo, $article['id'], 2);
}
unset($article); // libère la référence
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
