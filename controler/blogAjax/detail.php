<?php
include_once './blogAjax/head.php';
require_once "./model/BlogModel.php";
$model = new BlogModel();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Article introuvable.</p>";
    return;
}

$article = $model->getArticleById($_GET['id']);

if (!$article) {
    echo "<p>Article introuvable.</p>";
    return;
}
?>

<article class="article-detail">
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($article['date'])) ?></p>
    <p><strong>Auteur :</strong> <?= htmlspecialchars($article['auteur']) ?></p>

    <img src="<?= URL ?>src/img/blog/<?= htmlspecialchars($article['image']) ?>" alt="Image de l’article" class="article-image">

    <div class="article-description">
        <p><?= nl2br(htmlspecialchars($article['description'])) ?></p>
    </div>

    <a href="<?= URL ?>?page=blogAjax/body" class="btn-retour">← Retour au blog</a>
</article>