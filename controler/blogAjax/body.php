<?php
require_once "./model/BlogModel.php";
$model = new BlogModel();

$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $articles = $model->searchArticlesPaginated($search, $sort, $limit, $offset);
    $totalArticles = $model->countSearchArticles($search);
} else {
    $articles = $model->getAllArticlesPaginated($sort, $limit, $offset);
    $totalArticles = $model->countAllArticles();
}

$totalPages = ceil($totalArticles / $limit);
?>

<!-- Recherche et tri -->
<form method="get" class="blog-search-form">
    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
    <select name="sort">
        <option value="desc" <?= $sort === 'desc' ? 'selected' : '' ?>>Plus récent</option>
        <option value="asc" <?= $sort === 'asc' ? 'selected' : '' ?>>Plus ancien</option>
    </select>
    <button type="submit">Filtrer</button>
</form>

<!-- Affichage des articles -->
<div class="articles-grid">
    <?php if (empty($articles)): ?>
        <p>Aucun article trouvé.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <img src="<?= URL ?>src/img/blog/<?= htmlspecialchars($article['image']) ?>" alt="image">
                <h3><?= htmlspecialchars($article['title']) ?></h3>
                <p><?= htmlspecialchars($article['description']) ?></p>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($article['auteur']) ?></p>
                <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($article['date'])) ?></p>
                <a href="<?= URL ?>?page=blogAjax/detail&id=<?= $article['id'] ?>" class="btn-voir-plus">
                Voir plus
                </a>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&sort=<?= $sort ?>&search=<?= urlencode($search) ?>"
               class="<?= ($i === $page) ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
