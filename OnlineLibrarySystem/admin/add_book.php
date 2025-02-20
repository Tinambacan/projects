<?php
// Start session if needed
session_start();

// Database connection
include('connection.php'); // Ensure you have a file to establish DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $authors = $_POST['authors'];
    $edition = $_POST['edition'];
    $status = $_POST['status'];
    $quantity = (int)$_POST['quantity'];
    $department = $_POST['department'];

    $insertQuery = "INSERT INTO books (name, authors, edition, status, quantity, department) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = mysqli_prepare($db, $insertQuery)) {
        // Bind parameters (s for string, i for integer)
        mysqli_stmt_bind_param($stmt, 'ssssis', $name, $authors, $edition, $status, $quantity, $department);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // On success
            echo "<script>alert('Book added successfully!'); window.location.href='books.php';</script>";
        } else {
            // On failure
            echo "<script>alert('Error: Unable to add book. Please try again.'); window.location.href='add_book.php';</script>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement. Please try again.'); window.location.href='add_book.php';</script>";
    }
}

// Close the database connection
mysqli_close($db);
?>
