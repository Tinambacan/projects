<?php
include "connection.php";
// Set the timezone
date_default_timezone_set('Asia/Manila');
$currentDateTime = date('Y-m-d H:i:s');
if (isset($_POST['paid'])) {
    $issueID = $_POST['issue_id'];

    // Query to update the approve column to expired
    $updateQuery = "UPDATE issue_book 
                    SET approve = 'paid', `return` = '$currentDateTime' 
                    WHERE issue_id = '$issueID'";

    if (mysqli_query($db, $updateQuery)) {
        echo "<script>
                alert('Request marked as Paid successfully!');
                window.location.href = 'expire.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to mark request as Paid.');
              </script>";
    }
}
?>
