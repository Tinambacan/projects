<?php
// update_delivery.php

require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve parameters from the AJAX request
    $assignButtonId = $_POST['assign_button_id'];
    $radioButtonId = $_POST['radio_button_id'];

    $stmt = $conn->prepare("UPDATE delivery SET status = 1, partner_id = ? WHERE del_reference_id = ?");
    $stmt->bind_param("ii", $radioButtonId, $assignButtonId);

    if ($stmt->execute()) {
        echo 'Data updated successfully';
    } else {
        echo 'Error updating data';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method';
}
?>
