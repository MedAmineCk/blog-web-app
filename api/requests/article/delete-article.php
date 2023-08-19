<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Include DELETE method
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../../config/database.php';
require_once '../../models/Article.php';

// Get the database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Article class
$article = new Article($db);

// Get the article ID from the query parameters
$articleId = isset($_GET['id']) ? $_GET['id'] : null;

if ($articleId) {
    // Delete the article
    if ($article->deleteArticle($articleId)) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Article deleted successfully."));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error deleting article. ".$articleId));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing article ID."));
}



