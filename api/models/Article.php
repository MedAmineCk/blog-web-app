<?php
class Article {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createArticle($title, $subtitle, $content, $thumbnailUrl, $isPublic, $isPinned, $categoryIds, $selectedTags) {
        $query = "INSERT INTO articles (title, subtitle, content, thumbnail_url, is_public, is_pinned, tags) 
                VALUES (:title, :subtitle, :content, :thumbnailUrl, :isPublic, :isPinned, :tags)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':thumbnailUrl', $thumbnailUrl);
        $stmt->bindParam(':isPublic', $isPublic);
        $stmt->bindParam(':isPinned', $isPinned);
        $stmt->bindParam(':tags', $selectedTags);

        if ($stmt->execute()) {
            $articleId = $this->conn->lastInsertId();
            $this->insertArticleCategories($articleId, $categoryIds);
            return $articleId;
        } else {
            return false;
        }
    }


    private function insertArticleCategories($articleId, $categoryIds) {
        $query = "INSERT INTO article_categories (article_id, category_id) VALUES (:articleId, :categoryId)";
        $stmt = $this->conn->prepare($query);

        foreach ($categoryIds as $categoryId) {
            $stmt->bindParam(':articleId', $articleId);
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->execute();
        }
    }




    public function updateArticle($articleId, $title, $subtitle, $content, $thumbnailUrl, $isPublic, $isPinned, $selectedTags, $categoryIds) {
        $updateQuery = "UPDATE articles 
                SET title = :title, subtitle = :subtitle, content = :content, 
                    thumbnail_url = :thumbnailUrl, is_public = :isPublic, is_pinned = :isPinned, tags = :tags
                WHERE id = :articleId";

        $updateStatement = $this->conn->prepare($updateQuery);
        $params = [
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':content' => $content,
            ':thumbnailUrl' => $thumbnailUrl,
            ':isPublic' => $isPublic,
            ':isPinned' => $isPinned,
            ':tags' => $selectedTags,
            ':articleId' => $articleId
        ];

        if ($updateStatement->execute($params)) {
            $this->deleteArticleCategories($articleId);
            $this->insertArticleCategories($articleId, $categoryIds);

            return true;
        } else {
            return false;
        }
    }



    private function deleteArticleCategories($articleId) {
        $deleteQuery = "DELETE FROM article_categories WHERE article_id = :articleId";
        $deleteStatement = $this->conn->prepare($deleteQuery);
        $deleteStatement->bindParam(':articleId', $articleId, PDO::PARAM_INT);

        return $deleteStatement->execute();
    }




    public function deleteArticle($articleId) {
        // Delete article categories and tags relationships
        $this->deleteArticleCategories($articleId);

        // Delete the article itself
        $deleteArticleQuery = "DELETE FROM articles WHERE id = :articleId";
        $deleteArticleStatement = $this->conn->prepare($deleteArticleQuery);
        $deleteArticleStatement->bindParam(':articleId', $articleId, PDO::PARAM_INT);

        if ($deleteArticleStatement->execute()) {
            return true;
        } else {
            return false;
        }
    }



    public function getArticle($articleId) {
        $query = "SELECT * FROM articles WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$articleId]);

        if ($stmt->rowCount() > 0) {
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // Retrieve article categories and tags
            $article['categories'] = $this->getArticleCategories($articleId);

            return $article;
        } else {
            return null; // Article not found
        }
    }



    public function getArticles() {
        $query = "SELECT * FROM articles";
        $stmt = $this->conn->query($query);

        $articles = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Retrieve article categories and tags
            $row['categories'] = $this->getArticleCategories($row['id']);
            $articles[] = $row;
        }

        return $articles;
    }



    public function getArticleCategories($articleId) {
        $query = "SELECT c.id, c.name
              FROM categories c
              JOIN article_categories ac ON c.id = ac.category_id
              WHERE ac.article_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$articleId]);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }


}
