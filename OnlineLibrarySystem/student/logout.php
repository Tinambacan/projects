<?php
  session_start(); // Start session
  session_unset(); // Remove all session variables
  session_destroy(); // Destroy the session
  header("Location:index.php"); // Redirect to homepage
  exit();
?>
