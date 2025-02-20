<?php
session_start();

require_once('controller/db_connection.php');
header('Content-Type: application/json');

if (isset($_GET['trackingNumber'])) {
    $trackingNumber = $_GET['trackingNumber'];
    $_SESSION['trackingNumber'] = $trackingNumber;

    $sql = "SELECT d.del_reference_id, d.src_address, d.source_name, d.destination_address, d.receiver_name
            FROM delivery d
            WHERE d.del_reference_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode([$data]); 
    } else {
        echo json_encode([]);
    }
    $stmt->close();
} else {
    echo json_encode([]);
}
?>