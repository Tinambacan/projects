<?php
include "connection.php";
// Handle Approval Request
date_default_timezone_set('Asia/Manila');
if (isset($_POST['approve'])) {
    $issueID = $_POST['issue_id'];
    $expectedReturnDate = $_POST['expected_return_date'];
    $fine = $_POST['fine'];
    
    $currentDateTime = date('Y-m-d H:i:s');

    // Get the book ID from the issue_book table
    $bookQuery = "SELECT bid FROM issue_book WHERE issue_id = '$issueID'";
    $bookResult = mysqli_query($db, $bookQuery);

    if ($bookResult && mysqli_num_rows($bookResult) > 0) {
        $bookRow = mysqli_fetch_assoc($bookResult);
        $bookID = $bookRow['bid'];

        // Update the issue_book table
        $updateQuery = "UPDATE issue_book 
                        SET approve = 'approve', issue = '$currentDateTime', expected_return = '$expectedReturnDate', fine = '$fine' 
                        WHERE issue_id = '$issueID'";

        if (mysqli_query($db, $updateQuery)) {
            // Deduct 1 from the book quantity in the books table
            $updateBookQuery = "UPDATE books 
                                SET quantity = quantity - 1 
                                WHERE bid = '$bookID' AND quantity > 0";

            if (mysqli_query($db, $updateBookQuery)) {
                echo "<script>
                        alert('Request approved successfully!');
                        window.location.href = 'approve.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to update book quantity.');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Failed to approve request.');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Book ID not found for this request.');
              </script>";
    }
}
?>
