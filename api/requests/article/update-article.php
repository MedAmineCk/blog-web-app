<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("HTTP/1.1 200 OK");
    exit;
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, POST, DELETE, POST, GET, OPTIONS");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/database.php';
require_once '../../models/Article.php';

// Get the database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Article class
$article = new Article($db);

// Get the data from the request
$data = json_decode(file_get_contents("php://input"));

// Check if data is valid
if (
    isset($data->id)
) {
    // Extract the data
    $articleId = $data->id;
    $isPublic = $data->isPublic;
    $isPinned = $data->isPinned;
    $thumbnailUrl = $data->thumbnailUrl;
    $categoryIds = $data->selectedCategoriesIds;
    $selectedTags = $data->selectedTags;
    $title = $data->title;
    $subtitle = $data->subTitle;
    $content = $data->content;

    // Update the article
    if ($article->updateArticle($articleId, $title, $subtitle, $content, $thumbnailUrl, $isPublic, $isPinned, $selectedTags, $categoryIds)) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Article updated."));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Unable to update article."));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid data."));
}