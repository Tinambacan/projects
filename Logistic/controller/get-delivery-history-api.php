<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function getDeliveryHistory($deliveryReferenceNumber) {
    global $conn;

    if ($conn->connect_error) {
        return null;
    }


    $stmt = $conn->prepare("SELECT timestamp, checkpoint_location, description, status FROM history 
    JOIN delivery ON history.del_reference_id = delivery.del_reference_id WHERE delivery.del_reference_id = ?");   
    $stmt->bind_param('i', $deliveryReferenceNumber);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $result;
}

if (isset($_GET['delivery_reference_number'])) {
    $deliveryReferenceNumber = $_GET['delivery_reference_number'];

    if (!is_numeric($deliveryReferenceNumber)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'delivery_reference_number must be a number']);
        exit;
    }

    $deliveryHistory = getDeliveryHistory($deliveryReferenceNumber);

    if ($deliveryHistory !== null) {
        header('Content-Type: application/json');
        echo json_encode($deliveryHistory);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database connection error']);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'delivery_reference_number parameter is required']);
}
?>