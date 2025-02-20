<?php
// Include the database connection
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $studentid = $_POST['studentid'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validate input data
    if (empty($firstname) || empty($lastname) || empty($studentid) || empty($email)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'All fields are required.']); 
        exit;
    }

    // Update the student information
    $stmt = $db->prepare("UPDATE student SET firstname = ?, lastname = ?, email = ? WHERE studentid = ?");
    $stmt->bind_param('ssss', $firstname, $lastname, $email, $studentid);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Student information updated successfully.']);
    } else {
        // Error response
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update student information.']);
    }

    $stmt->close();
}

$db->close();
?>
