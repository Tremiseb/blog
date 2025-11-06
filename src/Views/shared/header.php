<header>
    <div class='header-container'>
        <img id="logo_header" src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="oco-logo">


        <form action="/search" method="get">
            <input type="text" name="query" placeholder="Rechercher..." />
            <button type="submit">Rechercher</button>
        </form>


        <form action="/search" method="get">
            <select name="category">
                <option value="" selected hidden>Toutes les catégories</option>
                <option value="books">Livres</option>
                <option value="movies">Films</option>
                <option value="music">Musique</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>

        <a href="<?= $creerArticle ?>" class="btn_login"><?= $boutonCreerArticle ?></a>
        <a href="<?= $redirection ?>" class="btn_login"><?= $bouton ?></a>
    </div>
</header>
