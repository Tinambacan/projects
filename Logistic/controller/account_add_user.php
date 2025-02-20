<?php
require_once('db_connection.php');
use PHPMailer\PHPMailer\PHPMailer;
header('Content-Type: application/json');

$riderName = $_POST['riderName'];
$vehicle = $_POST['vehicle'];
$username = $_POST['username'];
$email = $_POST['email'];
$passwordLength = 10;
$password = bin2hex(random_bytes($passwordLength));
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO delivery_partner (rider_name, vehicle, username, email,password) " .
    "VALUES (?, ?, ?, ?,?)"
);

$stmt->bind_param("sssss", $riderName, $vehicle, $username, $email,$hashedPassword);

if ($stmt->execute()) {
    $response = array('success' => true);
    sendUserCredentialsEmail($email,$username);

} else {
    $response = array('success' => false);
}

$stmt->close();
$conn->close();

echo json_encode($response);

function sendUserCredentialsEmail($recipientEmail,$username) {
    require '../vendor/autoload.php';

    $mail = new PHPMailer;
    $mail->isSMTP(); 
    $mail->SMTPAuth =true;

    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true; 
    $mail->Username = 'cybertech.intsys@gmail.com'; 
    $mail->Password = 'euln aufj tbqm ooxc'; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Port = 587; 
    $mail->addAddress($recipientEmail);
    $mail->Subject = 'Rider Credential';
    $mail->isHTML(true); 

    $email_template = "
        <h3>Here's your Credential Account </h3>
        <h4>Email address: $recipientEmail </h4>
        <h4>Username : $username </h4>
        <h4>Click this Link Below to Setup Your Password: </h4>
        <a href= 'http://localhost/project-cybertech/app/forgot-password.php?email=$recipientEmail'>Setup Password</a>
    ";

    $mail->Body = $email_template;

    if (!$mail->send()) {
        // Handle the error if email sending fails
        echo "Error sending email: " . $mail->ErrorInfo;
    }
}
?>

