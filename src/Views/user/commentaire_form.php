<?php
require_once __DIR__ . '/../../../src/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Ajouter un commentaire - Orange Cat Only</title>
    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/login/style.css" rel="stylesheet">
</head>
<body class="page-login">

<div class="form-container">
    <h1>Ajouter un commentaire</h1>

    <?php if (!empty($error)): ?>
        <div class="alert-error" style="color:red; margin-bottom:10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?page=commentaire/store">
        <input type="hidden" name="article_id" value="<?= (int)($_GET['id'] ?? 0) ?>">

        <textarea class="input" name="description" placeholder="Votre commentaire..." rows="5" required></textarea>

        <button class="btn_connexion" type="submit">Envoyer</button>
    </form>
</div>

</body>
</html>
