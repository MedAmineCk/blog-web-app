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

// Get the data from the request
$data = json_decode(file_get_contents("php://input"));
// Check if data is valid
if (
    isset($data)
) {
    // Extract the data
    $isPublic = $data->isPublic;
    $isPinnned = $data->isPinned;
    $thumbnailUrl = $data->thumbnailUrl;
    $selectedCategoriesIds = $data->selectedCategoriesIds;
    $selectedTags = $data->selectedTags;
    $title = $data->title;
    $subTitle = $data->subTitle;
    $content = $data->content;

    // Create the article
    $articleId = $article->createArticle($title, $subTitle, $content, $thumbnailUrl, $isPublic, $isPinnned, $selectedCategoriesIds, $selectedTags);

    if ($articleId) {
        http_response_code(201); // Created
        echo json_encode(array("message" => "Article created.", "articleId" => $articleId));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Unable to create article."));
    }

} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data provided."));
}



