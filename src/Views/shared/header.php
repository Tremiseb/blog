<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Database/db.php';
require_once __DIR__ . '/../../Database/userRepository.php';
require_once __DIR__ . '/../../Controllers/QueryController.php';

if ($_SESSION['role'] === 'admin') {
    $path = "admin/"; 
} else {
    $path = "user/";
}
$path;
$categories = getCategories($pdo); 
?>




<header>
    <div class='header-container'>
        <img id="logo_header" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="oco-logo">

    <form action="" method="POST">

    <input type="text" name="query" placeholder="Rechercher par le pseudo">
        <?php if(!empty($_POST['query'])){?>
            <button type="submit" name="action" value="reset">Effacer recherche</button>
        <?php } else { ?>
            <button type="submit" name="action" value="search"><?= htmlspecialchars($searchName) ?></button>
        <?php } ?>


        <select name="category">
            <option value="" hidden>Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="action" value="filter">Filter</button>

        <?php if(!empty($category)): ?>
            <button type="submit" name="action" value="reset">Retirer filtre</button>
        <?php endif; ?>
    </form>


    
        <a href="<?= BASE_URL ?>/public/index.php?page=<?= $path?>create" class="btn_login"><?= $boutonCreerArticle ?></a>
        <a href="<?= $redirection ?>" class="btn_login"><?= $bouton ?></a>
    </div>
</header>
