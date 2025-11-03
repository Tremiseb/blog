<?php require_once __DIR__ . '/../../src/config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Orange Cat Only') ?></title>

    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet"> 

</head>

<body>

    <?php
        require_once __DIR__ . '/shared/login.php';
    ?>
</body>
</html>
