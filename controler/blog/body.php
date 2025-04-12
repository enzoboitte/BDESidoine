<?php
global $G_sRacine;
require_once "$G_sRacine/model/BlogModel.php";

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'desc'; 
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$articleModel = new CArticle();

$totalArticles = $articleModel->countArticles($search);
$totalPages = ceil($totalArticles / $limit);

$articles = $articleModel->getArticles($search, $sort, $limit, $offset);

$currentPage = $page;

$G_sBackBtn = true;

include "$G_sRacine/view/menu.php";
include "$G_sRacine/view/blog.php";
