<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Manila');

function updateDelivery($deliveryReferenceNumber, $checkpointLocation, $description, $deliveryStatus) {
    global $conn;

    if ($conn->connect_error) {
        return false;
    }

    // Get the current timestamp in 'Asia/Manila' timezone
    $currentTimestampFormatted = date('Y-m-d H:i:s');

    $stmt = $conn->prepare('UPDATE delivery SET status = ? WHERE del_reference_id = ?');
    $stmt->bind_param('ii', $deliveryStatus, $deliveryReferenceNumber);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare('INSERT INTO history (description, timestamp, checkpoint_location, del_reference_id) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('sssi', $description, $currentTimestampFormatted, $checkpointLocation, $deliveryReferenceNumber);
    $stmt->execute();
    $stmt->close();

    return true;
}

if (isset($_POST['delivery_reference_number'], $_POST['checkpoint_location'], $_POST['description'], $_POST['delivery_status'])) {
    $deliveryReferenceNumber = $_POST['delivery_reference_number'];
    $checkpointLocation = $_POST['checkpoint_location'];
    $description = $_POST['description'];
    $deliveryStatus = $_POST['delivery_status'];

    if (!is_numeric($deliveryReferenceNumber) || !is_numeric($deliveryStatus)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    $updateResult = updateDelivery($deliveryReferenceNumber, $checkpointLocation, $description, $deliveryStatus);

    if ($updateResult) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['update_delivery_success' => 'success']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database connection error']);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Missing required parameters']);
}
?>