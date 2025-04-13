<?php
require_once "$G_sRacine/model/BlogModel.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>Article introuvable.</p>";
    return;
}

$model = new CArticle();
$article = $model->getArticleDetails($id);

if (!$article) {
    echo "<p>Article non trouvé.</p>";
    return;
}

$G_sBackBtn = true;
include "$G_sRacine/view/top_page.php";
include "$G_sRacine/view/articledetails.php";
