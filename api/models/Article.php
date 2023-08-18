<?php
class Article {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createArticle($title, $subtitle, $content, $thumbnailUrl, $isPublic, $categoryIds, $selectedTags) {
        $query = "INSERT INTO articles (title, subtitle, content, thumbnail_url, is_public, tags) 
                VALUES (:title, :subtitle, :content, :thumbnailUrl, :isPublic, :tags)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':thumbnailUrl', $thumbnailUrl);
        $stmt->bindParam(':isPublic', $isPublic);
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




    public function updateArticle($articleId, $title, $subtitle, $content, $thumbnailUrl, $isPublic, $categoryIds, $tagIds) {
        $stmt = $this->conn->prepare("UPDATE articles SET title=?, subtitle=?, content=?, thumbnail_url=?, is_public=? WHERE id=?");
        $stmt->bind_param("ssssii", $title, $subtitle, $content, $thumbnailUrl, $isPublic, $articleId);

        if ($stmt->execute()) {
            $stmt->close();

            $this->deleteArticleCategories($articleId); // Delete existing category relationships

            $this->insertArticleCategories($articleId, $categoryIds); // Insert new category relationships

            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    private function deleteArticleCategories($articleId) {
        $stmt = $this->conn->prepare("DELETE FROM article_categories WHERE article_id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $stmt->close();
    }



    public function deleteArticle($articleId) {
        // Delete article categories and tags relationships
        $this->deleteArticleCategories($articleId);

        // Delete the article itself
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
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
