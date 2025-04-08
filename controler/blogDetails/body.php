<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__){
    $G_sRacine = "..";
}

require_once "$G_sRacine/model/BlogModel.php";
$blogModel = new BlogModel();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$article = $blogModel->getArticleById($id);

if (!$article) {
    echo "<p style='color: white; text-align: center; margin-top: 2rem;'>Article introuvable.</p>";
} else {
    include "$G_sRacine/view/blogDetails.php";
}
