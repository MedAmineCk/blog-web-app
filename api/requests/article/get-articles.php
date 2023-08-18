<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("HTTP/1.1 200 OK");
    exit;
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, POST, GET, OPTIONS");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once '../../config/database.php';
require_once '../../models/Article.php';

// Get the database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Article class
$article = new Article($db);

// Fetch all articles
$articles = $article->getArticles();

// Check if articles were fetched
if ($articles) {
    http_response_code(200); // OK
    echo json_encode($articles);
} else {
    http_response_code(404); // Not Found
    echo json_encode(array("message" => "No articles found."));
}