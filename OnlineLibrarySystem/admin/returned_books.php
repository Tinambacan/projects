<?php
include "connection.php";
// Handle Approval Request
date_default_timezone_set('Asia/Manila');
if (isset($_POST['return'])) {
    $issueID = $_POST['issue_id'];
    $currentDateTime = date('Y-m-d H:i:s');

    // Get the book ID from the issue_book table
    $bookQuery = "SELECT bid FROM issue_book WHERE issue_id = '$issueID'";
    $bookResult = mysqli_query($db, $bookQuery);

    if ($bookResult && mysqli_num_rows($bookResult) > 0) {
        $bookRow = mysqli_fetch_assoc($bookResult);
        $bookID = $bookRow['bid'];

        // Update the issue_book table
        $updateQuery = "UPDATE issue_book 
                        SET approve = 'return', `return` = '$currentDateTime' 
                        WHERE issue_id = '$issueID'";

        if (mysqli_query($db, $updateQuery)) {
            // Add 1 to the book quantity in the books table
            $updateBookQuery = "UPDATE books 
                                SET quantity = quantity + 1 
                                WHERE bid = '$bookID'";

            if (mysqli_query($db, $updateBookQuery)) {
                echo "<script>
                        alert('Book returned successfully!');
                        window.location.href = 'return.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to update book quantity.');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Failed to update return request.');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Book ID not found for this request.');
              </script>";
    }
}


?>