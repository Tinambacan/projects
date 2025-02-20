<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Check if partner_id is provided in the request
if (isset($_GET['partner_id'])) {
    $partner_id = $_GET['partner_id'];
    $sql = "SELECT d.del_reference_id, d.receiver_name, d.destination_address, d.source_name, d.src_address, d.status, dp.rider_name, dp.partner_id
        FROM delivery d
        LEFT JOIN delivery_partner dp ON d.partner_id = dp.partner_id
        WHERE d.partner_id = ? 
        GROUP BY d.del_reference_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $partner_id);
} else {
    // If partner_id is not provided, fetch all delivery data
    $sql = "SELECT d.del_reference_id, d.receiver_name, d.destination_address, d.source_name, d.src_address, d.status, dp.rider_name, dp.partner_id
            FROM delivery d
            LEFT JOIN delivery_partner dp ON d.partner_id = dp.partner_id
            GROUP BY d.del_reference_id";

    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'order_id' => $row['del_reference_id'],
            'receiver_name' => $row['receiver_name'],
            'destination_address' => $row['destination_address'],
            'source_name' => $row['source_name'],
            'src_address' => $row['src_address'],
            'status' => $row['status'],
            'rider_name' => $row['rider_name'],
            'partner_id' => $row['partner_id']
        );
    }
    echo json_encode($data); // Send the data as JSON
} else {
    echo json_encode(
        array(
            "status" => "error",
            "message" => "No delivery history found"
        )
    );
}

$stmt->close();
?>