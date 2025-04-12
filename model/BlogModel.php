<?
require_once 'CPDOModel.php';

class CArticle extends CPDOModel
{
    public function getArticles($search, $sort, $limit, $offset) {
        $order = ($sort === 'asc') ? 'ASC' : 'DESC';
        $sql = "SELECT * FROM article 
                WHERE title LIKE :search OR description LIKE :search OR auteur LIKE :search 
                ORDER BY date $order 
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
        $sql = "SELECT COUNT(*) FROM article 
                WHERE title LIKE :search OR description LIKE :search OR auteur LIKE :search";
        $stmt = $this->F_cGetDB()->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
