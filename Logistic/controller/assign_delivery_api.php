<?php
require_once('db_connection.php');
header('Content-Type: application/json');

$sql = "SELECT del_reference_id, source_name, receiver_name, status FROM delivery WHERE status = 0";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit; // Ensure no additional output is sent

    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Error preparing the statement"));
    exit; // Ensure no additional output is sent
}
$conn->close();
?>
