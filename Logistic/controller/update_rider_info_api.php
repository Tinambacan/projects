<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $riderName = $_POST['rider_name']; 
    $newRiderName = $_POST['new_rider_name'];
    $newVehicle = $_POST['new_vehicle'];
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    $checkQuery = "SELECT partner_id 
                   FROM delivery_partner 
                   WHERE rider_name = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $riderName);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
  
        $updateQuery = "UPDATE delivery_partner SET rider_name = ?, "
                        . "vehicle = ?, username = ?, password = ? "
                        . "WHERE rider_name = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($updateStmt, "sssss", 
                                $newRiderName, 
                                $newVehicle, 
                                $newUsername, 
                                $newPasswordHash, 
                                $riderName);

        if (mysqli_stmt_execute($updateStmt)) {
            $response = array('status' => 'success', 
                              'message' => 'Details updated successfully');
        } else {
            $response = array('status' => 'error', 
                              'message' => 'Error updating details');
        }

        mysqli_stmt_close($updateStmt);
    } else {
        $response = array('status' => 'error', 
                          'message' => 'Rider name not found.');
    }

    mysqli_stmt_close($checkStmt);
} else {
    $response = array('status' => 'error', 
                      'message' => 'Invalid request method');
}

echo json_encode($response);
mysqli_close($conn);
?>
