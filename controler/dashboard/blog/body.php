<?php
require_once "$G_sRacine/model/blog.php"; // Bien utiliser $G_sRacine

$blogModel = new Blog(); // instancie l'objet
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
    default:
        $articles = $blogModel->getAllPosts();
        include "$G_sRacine/view/dashboard/blog.php";
        break;
}
