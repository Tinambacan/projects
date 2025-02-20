<?php
require_once('db_connection.php');
header('Content-Type: application/json');

$query = "SELECT partner_id, username, password, rider_name, vehicle, app
          FROM delivery_partner
          WHERE partner_id NOT IN (SELECT partner_id FROM delivery WHERE status = '1')";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_execute($stmt);

    // Bind the result variables
    mysqli_stmt_bind_result($stmt, $partnerId, $username, $password, $riderName, $vehicle, $app);

    $response = [];

    // Fetch values
    while (mysqli_stmt_fetch($stmt)) {
        array_push($response, array(
            'partner_id' => $partnerId,
            'username' => $username,
            'password' => $password,
            'rider_name' => $riderName,
            'vehicle' => $vehicle,
            'app' => $app
        ));
    }

    echo json_encode($response);

    mysqli_stmt_close($stmt);
} else {
    // Handle error if the statement preparation fails
    echo json_encode(array('error' => 'Failed to prepare statement.'));
}

$conn->close();
?>
