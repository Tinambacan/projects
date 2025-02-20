<?php
// Database connection
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['bookId'];
    $newStatus = $_POST['newStatus'];

    // Update query using prepared statement
    $query = "UPDATE books SET status = ? WHERE bid = ?";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $newStatus, $bookId);
        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            echo 'error';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'error';
    }
}

mysqli_close($db);
?>
