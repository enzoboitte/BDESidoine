<?php

class BlogModel {

    private $pdo;

    public function __construct() {

        $host = 'localhost';
        $dbname = 'BDESidoine';  
        $username = 'root';     
        $password = '';          

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getAllArticles($sort = 'desc') {
        $order = ($sort === 'asc') ? 'ASC' : 'DESC';
        $sql = "SELECT * FROM articles ORDER BY date $order";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchArticles($search, $sort = 'desc') {
        $order = ($sort === 'asc') ? 'ASC' : 'DESC';
        $sql = "SELECT * FROM articles 
                WHERE title LIKE :search OR author LIKE :search
                ORDER BY date $order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'search' => '%' . $search . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getArticleById($id) {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
