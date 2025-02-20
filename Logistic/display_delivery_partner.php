<?php
require_once('controller/db_connection.php');

$query = "SELECT rider_name, vehicle, username, email FROM delivery_partner";
$result = mysqli_query($conn, $query);

if ($result) {
    $deliveryPartners = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $deliveryPartners[] = $row;
    }

    mysqli_free_result($result);
} else {
    echo 'Error fetching data from delivery_partner table';
}

mysqli_close($conn);
?>
