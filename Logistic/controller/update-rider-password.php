<?php
require_once('db_connection.php');
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$passwordUpdate = "UPDATE delivery_partner SET password = '$hashedPassword' WHERE email ='$email'";
$passwordUpdateSuccessful = mysqli_query($conn,$passwordUpdate);

if ($passwordUpdateSuccessful) {
  echo 'success';
} else {
  echo 'Error updating password';
}
?>