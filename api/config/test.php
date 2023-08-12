<?php
// Include the Database class
include_once 'database.php';

// Create a new Database instance
$database = new Database();

// Get the database connection
$conn = $database->getConnection();

// Check if the connection is successful
if ($conn) {
    // Define a sample query
    $query = "SELECT * FROM users LIMIT 1";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output the result
    if ($row) {
        print_r($row);
    } else {
        echo "Database connection successful, but no records found.";
    }
} else {
    echo "Database connection failed.";
}
