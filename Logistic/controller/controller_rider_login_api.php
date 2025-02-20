<?php
require_once('db_connection.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT partner_id, username, email, app, password FROM delivery_partner WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $partnerID, $dbUsername, $email,$application, $hashPassword);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hashPassword)) {
            $response = array('status' => 'success', 'message' => 'Login successful!', 
            'username' => $dbUsername, 'partner_id' => $partnerID, 'email' => $email, 'application' => $application);
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid password');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid username');
    }

    mysqli_stmt_close($stmt);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
}
echo json_encode($response);
mysqli_close($conn);
/*  Insert Into
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "INSERT INTO delivery_partner (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password before storing
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        $response = array('status' => 'success', 'message' => 'User registration successful!', 'username' => $username);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to register user');
    }

    mysqli_stmt_close($stmt);
*/
/*  SELECT 
    $query = "SELECT * FROM delivery_partner";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $response = [];

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($response, array(
            'partner_id' => $row["partner_id"],
            'username' => $row["username"],
            'password' => $row["password"],
            'rider_name' => $row["rider_name"],
            'vehicle' => $row["vehicle"]
        ));
    }

    echo json_encode($response);

    mysqli_stmt_close($stmt);


/*  UPDATE STMT
    

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Assuming $conn is your database connection
    $query = "UPDATE delivery_partner SET password = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the new password before storing
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username);

    if (mysqli_stmt_execute($stmt)) {
        $response = array('status' => 'success', 'message' => 'Password update successful for user: ' . $username);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update password');
    }

    mysqli_stmt_close($stmt);
*/
// Output the response as JSON

?>
