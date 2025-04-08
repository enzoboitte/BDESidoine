<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $G_sRacine = "..";
}

require_once "$G_sRacine/model/BlogModel.php";
$blogModel = new BlogModel();
$articles = $blogModel->getAllArticles();
$search = $_GET['search'] ?? null;
$sort = $_GET['sort'] ?? 'desc';

if ($search) {
    $articles = $blogModel->searchArticles($search, $sort);
} else {
    $articles = $blogModel->getAllArticles($sort);
}

include "$G_sRacine/view/blog.php";
