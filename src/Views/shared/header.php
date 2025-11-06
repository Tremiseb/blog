<?php
require_once __DIR__ . '/../../Controllers/HeaderController.php';

$categories = getCategories($pdo); 
?>

<header>
    <div class='header-container'>
        <img id="logo_header" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="oco-logo">

        <form action="" method="POST">
            <input type="text" name="query" placeholder="Rechercher par le pseudo" />
            <button type="submit">Rechercher</button>
        </form>

        <form action="?page=user/accueil" method="get">
            <select name="category">
                <option value="" hidden>Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>
        
        <a href="<?= BASE_URL ?>/public/index.php?page=user/create" class="btn_login"><?= $boutonCreerArticle ?></a>
        <a href="<?= $redirection ?>" class="btn_login"><?= $bouton ?></a>
    </div>
</header>
