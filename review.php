<?php
// Set the content type to JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
// Get the raw POST data
$data = file_get_contents('php://input');

// Decode the JSON data
$json_data = json_decode($data, true);

// Validate the data
if (empty($json_data['product_id']) || !is_numeric($json_data['product_id'])) {
    echo json_encode(['error' => 'Invalid or missing product_id']);
    exit;
}

if (empty($json_data['user_id']) || !is_numeric($json_data['user_id'])) {
    echo json_encode(['error' => 'Invalid or missing user_id']);
    exit;
}

if (empty(trim($json_data['review_details']))) {
    echo json_encode(['error' => 'Invalid or missing review']);
    exit;
}

// Database credentials
$db_host = 'localhost';
$db_name = 'test';
$db_user = 'root';
$db_pass = '';

// Create a new mysqli instance
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for any connection errors
if ($conn->connect_error) {
    echo json_encode(['error' => 'Could not connect to database: ' . $conn->connect_error]);
    exit;
}
$sql = "INSERT INTO review (product_id, user_id, review_details)
VALUES ('".$json_data['product_id']."', '".$json_data['user_id']."', '".$json_data['review_details']."')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => 'Review submitted successfully']);
} else {
    echo json_encode(['error' => 'Could not submit review: ' . $conn->error]);
}

$conn->close();
?>
