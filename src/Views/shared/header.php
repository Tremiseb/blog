
<header>
    <div class='header-container'>
        <img id="logo_header" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="oco-logo">
        
        <nav>
            <ul>
                <?php foreach ($nav as $item): ?>
                    <li><a href="#" target="_blank"><?= htmlspecialchars($item) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>    
    
        <a href="<?= $creerArticle ?>" class="btn_login"><?php echo($boutonCreerArticle)  ?></a>

        <a href="<?= $redirection ?>" class="btn_login"><?php echo($bouton)  ?></a>
    </div>
</header>


