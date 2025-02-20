<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // User is not logged in, redirect to the login page
    header('Location: logistics_adminlogin.php');
}
$adminID = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Cybertech Logistics</title> -->
    <link rel="shortcut icon" type="image/png" href="images/logistic logo-circle.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/header1.css">
</head>

<body>
    <div class="sidebar">
        <div class="logistic-logo">
            <img src="images/logistic logo-circle.png" alt="" class="admin-logo">
            <p>Cybertech<br>Logistics</p>
        </div>
        <div class="link-tag">
            <a href="assign_delivery.php" class="list-group-item" onclick="setActive(this)">
                <i class="fa-solid fa-truck fa-lg"></i>Assign Delivery</a>
            <a href="delivery_list.php" class="list-group-item" onclick="setActive(this)">
                <i class="fas fa-clipboard-list fa-lg"></i>Delivery List</a>
            <a href="account_rider.php" class="list-group-item" onclick="setActive(this)">
                <i class="far fa-user-circle fa-lg"></i>Accounts</a>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <div class="navbar">
            <img src="images/adminAccount.jpg" alt="" class="admin-account">
            <p><b>CyberTech</b><br>Administrator</p>
        </div>

        <!-- Logout Button -->
        <div class="logout-button">
            <a href="#" class="list-group-item" id="confirmLogout">
                <i class="fas fa-sign-out-alt fa-lg"></i>Logout
            </a>
        </div>

        <!-- Logout Modal -->
        <div id="logoutModal" class="modal_log">
            <div class="content-logout">
                <span class="logout-close">&times;</span>
                <p>Are you sure you want to logout?</p>
                <button id="confirmYes">Yes</button>
                <button id="confirmNo">No</button>
            </div>
        </div>