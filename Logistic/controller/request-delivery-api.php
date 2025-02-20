<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Check if any required form field is empty
if (empty($_POST['source_address']) || empty($_POST['source_name']) || empty($_POST['destination_address']) || empty($_POST['destination_name'])) {
    $response = array(
        "success" => false,
        "message" => "Error: Incomplete form data. Please provide values for all required fields."
    );
    echo json_encode($response);
    exit;
}

// Retrieve form data
$source_address = $_POST['source_address'] ?? '';
$source_name = $_POST['source_name'] ?? '';
$destination_address = $_POST['destination_address'] ?? '';
$destination_name = $_POST['destination_name'] ?? '';
$status = '0';

// Prepare and bind parameters for the SQL query
$stmt = $conn->prepare("INSERT INTO delivery (del_reference_id, src_address, 
    source_name, destination_address, receiver_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param(
    "issss",
    $delReferenceID,
    $source_address,
    $source_name,
    $destination_address,
    $destination_name
);

$delReferenceID = rand(10000, 99999);

$response = [];

if ($stmt->execute()) {
    $response = array(
        "success" => true,
        "del_reference_id" => $delReferenceID,
        "message" => "New record created successfully with Reference ID: 
            $delReferenceID"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error: " . $stmt->error
    );
}

echo json_encode($response);

// Close the statement and database connection
$stmt->close();
$conn->close();
?>