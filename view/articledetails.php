<section class="article-detail">
    <h1><?= htmlspecialchars($article['titreP']) ?></h1>
    <p><strong>Auteur :</strong> <?= htmlspecialchars($article['idC']) ?> |
       <strong>Date :</strong> <?= htmlspecialchars($article['dateP']) ?></p>

    <div class="article-gallery">
        <?php foreach ($article['images'] as $img): ?>
            <img src="src/img/blog/<?= htmlspecialchars($img) ?>" alt="Image de l'article">
        <?php endforeach; ?>
    </div>

    <div class="article-description">
        <p><?= nl2br(htmlspecialchars($article['descriptionP'])) ?></p>
    </div>

    <a href="index.php?link=blog" class="btn-retour">← Retour au blog</a>
</section>