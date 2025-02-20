<?php
session_start();
require_once('db_connection.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT admin_id, username, password FROM courier_admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $adminId, $dbUsername, $hashPassword);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hashPassword)) {
            $_SESSION['admin_id'] = $adminId;
            $response = array('status' => 'success', 'message' => 'Login successful!', 'username' => $dbUsername);
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid username or password');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid username or password');
    }

    mysqli_stmt_close($stmt);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
}
echo json_encode($response);

mysqli_close($conn);
?>