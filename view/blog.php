<?php 
global $G_sPath, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/blog/blog.css');"; 
?>

<section class="blog-container">
    <form class="blog-controls" method="GET" action="index.php">
        <input type="hidden" name="link" value="blog">
        <input type="text" name="search" placeholder="Recherche..." class="search-input"
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

        <select name="sort" class="sort-button" onchange="this.form.submit()">
            <option value="">Trier</option>
            <option value="asc" <?= (($_GET['sort'] ?? '') === 'asc') ? 'selected' : '' ?>>Date ↑</option>
            <option value="desc" <?= (($_GET['sort'] ?? '') === 'desc') ? 'selected' : '' ?>>Date ↓</option>
        </select>
    </form>

    <div class="articles-list">
        <?php if (empty($articles)): ?>
            <p class="no-article">Aucun article trouvé.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <div class="article-image">
                        <img src="src/img/blog/<?= htmlspecialchars($article['_imageP']) ?>" alt="Image poste">
                    </div>
                    <div class="article-content">
                        <h2 class="article-title"><?= htmlspecialchars($article['titreP']) ?></h2>
                        <p class="article-description"><?= htmlspecialchars($article['descriptionP']) ?></p>
                        <div class="article-meta">
                            <span><?= htmlspecialchars($article['auteur'] ?? 'Auteur inconnu') ?></span>
                            <span><?= htmlspecialchars($article['dateP']) ?></span>
                        </div>
                        <div class="article-bottom">
                            <a class="see-more-btn" href="index.php?link=articledetails&id=<?= $article['idP'] ?>">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="index.php?link=blog&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&page=<?= $i ?>" 
           class="<?= ($i == $page) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>