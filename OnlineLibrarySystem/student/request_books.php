<?php
include "connection.php";
session_start();
if (isset($_POST['submit_request'])) {
    // Check if user is logged in
    if (isset($_SESSION['studentid'])) {
        // Sanitize input
        $bookID = mysqli_real_escape_string($db, $_POST['bookID']);
        $studentID = $_SESSION['studentid'];
        $status = "pending";

        // Check if the book is available
        $checkBookQuery = "SELECT status FROM books WHERE bid = '$bookID'";
        $bookResult = mysqli_query($db, $checkBookQuery);

        if ($bookResult && mysqli_num_rows($bookResult) > 0) {
            $bookRow = mysqli_fetch_assoc($bookResult);

            if ($bookRow['status'] === 'Available') {
                // Use prepared statements for secure insertion
                $stmt = $db->prepare("INSERT INTO issue_book (studentid, bid, approve, issue) VALUES (?, ?, ?, NULL)");
                $stmt->bind_param("sss", $studentID, $bookID, $status);

                if ($stmt->execute()) {
                    echo "<script type='text/javascript'>
                            alert('Book requested successfully!');
                            window.location='request.php';
                          </script>";
                } else {
                    echo "<script type='text/javascript'>
                            alert('Failed to request the book. Please try again.');
                          </script>";
                }
                $stmt->close();
            } else {
                echo "<script type='text/javascript'>
                        alert('The selected book is not available for request.');
                        window.location='books.php';
                      </script>";
            }
        } else {
            echo "<script type='text/javascript'>
                    alert('Book not found.');
                    window.location='request.php';
                  </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('You need to log in to request a book.');
                window.location='login.php';
              </script>";
    }
}

?>
