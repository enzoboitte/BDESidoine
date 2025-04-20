<?php
require_once "$G_sRacine/model/BlogModel.php"; 
// ROUTAGE PRINCIPAL
$blogModel = new CArticle(); 
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
    default:
        $articles = $blogModel->getAllPosts();
        include "$G_sRacine/view/dashboard/blog.php";
        break;
}

// TRAITEMENT DU FORMULAIRE AJOUT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $titre = $_POST['titre'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = '';

    // Upload de l’image
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "$G_sRacine/src/img/blog/";
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $fileName;
        }
    }

    // Ajout en base via modèle
    require_once "$G_sRacine/model/BlogModel.php";
    $blog = new Blog();
    $blog->addPost($titre, $date, $description, $image);

    // Redirection après ajout
    header("Location: " . $G_sPath . "/dashboard/blog");
    exit;
}

    