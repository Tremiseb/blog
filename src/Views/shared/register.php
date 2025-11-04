<?php
    require_once __DIR__ . '/../../../src/config.php'; 
?>


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
        <img id="logo_login" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="Orange-Cat-Only-logo">
        <form method="POST" action="">
            <input class="input" type="text" name="email" placeholder="email@univ-lyon1.fr" required>
            <input class="input" type="text" name="username" placeholder="Xx_Super_Orange_Cat_xX" required>

            <input class="input" type="password" name="password" placeholder="Mot de passe" required>
            <select class="dropdown" name="role" id="role">
                <?php if (!empty($roles)) : ?>
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Aucun rôle disponible</option>
                <?php endif; ?>
            </select>

            <button class="btn_connexion" type="submit">Créer le compte</button>

        </form>
    </div>

</body>
</html>