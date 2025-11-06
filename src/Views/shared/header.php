<?php
require_once __DIR__ . '/../../Controllers/HeaderController.php';
require_once __DIR__ . '/../../Controllers/QueryController.php';

$path;
$categories = getCategories($pdo); 
?>

<header>
    <div class='header-container'>
        <img id="logo_header" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="oco-logo">

        <form action="" method="POST">
            <input type="text" name="query" placeholder="Rechercher par le pseudo" />
            <button type="submit"><?php echo($searchName)?> </button>
        </form>

        <form action="" method="POST">
            <select name="category">
                <option value="" hidden>Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit"> <?php echo($searchCategory)?></button>
        </form>
        
        <?php

        if ($_SESSION['role'] === 'admin') {
            $path = "admin/create";
        } else {
            $path = "user/create";
        }
        ?>

        <a href="<?= BASE_URL ?>/public/index.php?page=<?= $path?>" class="btn_login"><?= $boutonCreerArticle ?></a>
        <a href="<?= $redirection ?>" class="btn_login"><?= $bouton ?></a>
    </div>
</header>
