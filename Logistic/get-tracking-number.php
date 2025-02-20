<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['trackingNumber'])) {
    $trackingNumber = $_SESSION['trackingNumber'];
    echo json_encode(['trackingNumber' => $trackingNumber]);
} else {
    echo json_encode(['trackingNumber' => null]);
}
?>