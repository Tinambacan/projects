<?php
session_start();
include('connection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the current password and new password from the Ajax request
    $currentPassword = mysqli_real_escape_string($db, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($db, $_POST['newPassword']);

    $employeeid = $_SESSION['employeeid'];

    // Query to check if the current password matches the one in the database
    $query = "SELECT password FROM admin WHERE employeeid = '$employeeid'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify the current password
        if (password_verify($currentPassword, $row['password'])) {
            // Current password is correct, update with the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE admin SET password = '$hashedPassword' WHERE employeeid = '$employeeid'";

            if (mysqli_query($db, $updateQuery)) {
                echo 'Password updated successfully!';
                session_unset();
                session_destroy();
            } else {
                echo 'Error updating password.';
            }
        } else {
            echo 'Current password is incorrect.';
        }
    } else {
        echo 'admin not found.';
    }
}
?>
