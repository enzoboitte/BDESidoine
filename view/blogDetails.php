<section class="blog-details">
    <div class="wave wave-top"></div>

    <div class="container">
        <h1 class="bde-heading">BDE<br><span>Sidoine Apollinaire</span></h1>

        <div class="blog-info">
            <p><strong><?= htmlspecialchars($article['title']) ?></strong></p>
            <p><?= htmlspecialchars($article['date']) ?></p>
            <p>Rédigé par <?= htmlspecialchars($article['auteur']) ?></p>
        </div>

        <div class="blog-content">
            <img src="src/img/blog/<?= htmlspecialchars($article['image']) ?>" alt="Image article">

            <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
        </div>

        <p class="footer-signature">
            Édité par - <?= htmlspecialchars($article['auteur']) ?> / Publié par - <?= htmlspecialchars($article['author']) ?>
        </p>
    </div>

    <div class="wave wave-bottom"></div>
</section>
