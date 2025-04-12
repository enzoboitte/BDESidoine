<?php 
global $G_sPath, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/blog/blog.css');"; ?>
<section class="blog-container">
    <form class="blog-controls" method="GET" action="index.php">
        <input type="hidden" name="link" value="blog">
        <input type="text" name="search" placeholder="Recherche..." class="search-input"
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        
        <select name="sort" class="sort-button" onchange="this.form.submit()">
            <option value="">Trier </option>
            <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'selected' : '' ?>>Date ↑</option>
            <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'selected' : '' ?>>Date ↓</option>
        </select>
    </form>


    <div class="articles-list">
    <?php if (count($articles) === 0): ?>
        <p class="no-article">Aucun article trouvé.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <div class="article-image">
                    <img src="src/img/blog/<?= htmlspecialchars($article['image']) ?>" alt="Image de l'article">
                </div>
                <div class="article-content">
                    <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="article-description"><?= htmlspecialchars($article['description']) ?></p>
                    <div class="article-meta">
                        <span><?= htmlspecialchars($article['auteur']) ?></span> 
                        <span><?= htmlspecialchars($article['date']) ?></span>
                    </div>
                    <div class="article-bottom">
                        <a class="see-more-btn" href="index.php?link=blogAjax&id=<?= $article['id'] ?>">Voir plus</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</section>