<?php require_once __DIR__ . '/../../config.php'; ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Login - Team Jardin</title>
    <link href="<?= BASE_URL ?>/public/assets/shared/login/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/shared/charte-graphique.css" rel="stylesheet">
</head>
<body class="page-login">
    <div class="form-container">
        <img id="logo_login" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="Logo Team Jardin">
        <form method="POST" action="<?= BASE_URL ?>/public/index.php?page=login">
            <input class="input" type="text" name="email" placeholder="email@univ-lyon1.fr" required>
            <input class="input" type="password" name="password" placeholder="Mot de passe" required>
            <button class="btn_connexion" type="submit">Connexion</button>

            <a href="<?= BASE_URL ?>/public/index.php?page=register">Nouveau ? Créer un compte !</a>

        </form>
    </div>

</body>
</html>
