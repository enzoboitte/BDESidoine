<?php

require_once "$G_sRacine/model/PDOModel.php";

class CArticle extends CPDOModel
{
    public function getArticles($search, $sort, $limit, $offset) {
        $order = ($sort === 'asc') ? 'ASC' : 'DESC';
        $sql = "SELECT * FROM poste 
                WHERE titreP LIKE :search OR descriptionP LIKE :search 
                ORDER BY dateP $order 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->F_cGetDB()->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countArticles($search) {
        $sql = "SELECT COUNT(*) FROM poste 
                WHERE titreP LIKE :search OR descriptionP LIKE :search";
        $stmt = $this->F_cGetDB()->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getArticleDetails($id) {
        $sql = "SELECT * FROM poste WHERE idP = :id";
        $stmt = $this->F_cGetDB()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch();

        if ($article) {
            $sqlImgs = "SELECT _imageP FROM poste WHERE idP = :id";
            $stmtImg = $this->F_cGetDB()->prepare($sqlImgs);
            $stmtImg->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtImg->execute();
            $article['images'] = $stmtImg->fetchAll(PDO::FETCH_COLUMN);
        }

        return $article;
    }

    public function getAllPosts()
    {
        $stmt = $this->F_cGetDB()->prepare("SELECT idP, dateP, titreP, descriptionP, _imageP FROM poste ORDER BY dateP DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addPost($titre, $date, $description, $image = null) {
        $pdo = F_cGetDB();
        $query = "INSERT INTO poste (titreP, dateP, descriptionP, _imageP, idC) VALUES (?, ?, ?, ?, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$titre, $date, $description, $image]);
    }
    
}