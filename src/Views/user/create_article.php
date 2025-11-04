<?php
require_once __DIR__ . '/../../../src/config.php';
require_once __DIR__ . '/../../../src/Database/db.php';
require_once __DIR__ . '/../../../src/Database/articleRepository.php'; 

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$categories = getCategories($pdo); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Créer un article - Orange Cat Only</title>
    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/login/style.css" rel="stylesheet">
</head>
<body class="page-login">
    <div class="form-container">
        <h1>Créer un article</h1>

        <?php if (!empty($error)): ?>
            <div class="alert-error" style="color:red; margin-bottom:10px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input class="input" type="text" name="titre" placeholder="Titre de l'article" required>
            <textarea class="input" name="description" placeholder="Description de l'article" rows="5" required></textarea>

        <select class="dropdown" name="categorie_id" required>
            <option value="" disabled selected hidden>Choisir une catégorie</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>

            <button class="btn_connexion" type="submit">Créer l'article</button>
        </form>
    </div>
</body>
</html>
