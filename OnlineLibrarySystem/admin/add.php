<?php
include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .navbar-inverse {
            background-color: #526E48;
            border-color: #526E48;
        }

        .navbar-inverse .navbar-brand {
            color: white !important;
            cursor: default;
            font-weight: bold;
        }

        .navbar-nav li a.active {
            color: white !important;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px auto;
            padding: 0 15px;
        }

        .search-container {
            margin: 20px auto;
            text-align: center;
        }

        .search-container input[type="text"] {
            width: 60%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-container button {
            background-color: #526E48;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #62825D;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .table {
            margin-top: 20px;
            background-color: white;
            border: 1px solid #ddd;
            width: 100%;
        }

        .table th {
            background-color: #526E48;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .sidenav {
            height: 100%;
            margin-top: 50px;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #526E48;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

        input:focus {
            outline: 2px solid #526E48;
            border: 1px solid #526E48;
        }

        button.btn-default {
            background-color: #526E48;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
        }

        button.btn-default:hover {
            background-color: #62825D;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <span class="navbar-brand">LibraLink</span>
        </div>
        <ul class="nav navbar-nav">
            <li class="<?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'active' : ''; ?>"><a href="index.php">HOME</a></li>
            <li class="<?php echo ($_SERVER['PHP_SELF'] == '/books.php') ? 'active' : ''; ?>"><a href="books.php">BOOKS</a></li>
            <li class="<?php echo ($_SERVER['PHP_SELF'] == '/feedback.php') ? 'active' : ''; ?>"><a href="feedback.php">FEEDBACK</a></li>
            <?php
            if (isset($_SESSION['login'])) {
                echo '<li class="' . ($_SERVER['PHP_SELF'] == '/profile.php' ? 'active' : '') . '"><a href="profile.php">PROFILE</a></li>';
                echo '<li class="' . ($_SERVER['PHP_SELF'] == '/student_info.php' ? 'active' : '') . '"><a href="student_info.php">STUDENT INFO</a></li>';
            }
            ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <?php
            if (isset($_SESSION['login'])) {
                echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> LOGOUT</a></li>';
            } else {
                echo '<li><a href="../login.php"><span class="glyphicon glyphicon-log-in"></span> LOGIN</a></li>';
                echo '<li><a href="registration.php"><span class="glyphicon glyphicon-user"></span> SIGN UP</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div style="color: white; margin-left: 60px; font-size: 20px;">
        <?php
        echo "Welcome, " . $_SESSION['employeeid'];
        ?>
    </div>
    <br>
    <a href="add.php">Add Books</a>
    <a href="request.php">Book Request</a>
    <a href="approve.php">Approved Book Request</a>
    <a href="return.php">Returned Books</a>
    <a href="expire.php">Expired Books Request</a>
</div>
<div id="main">
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">
        <h2 style="color:#526E48; font-family:Arial, Helvetica, sans-serif; text-align: center; font-weight: bold;">Add New Books</h2><br>
        <form action="add_book.php" method="POST">
            <input type="text" name="name" class="form-control" placeholder="Book Name" required=""><br>
            <input type="text" name="authors" class="form-control" placeholder="Authors Name" required=""><br>
            <input type="text" name="edition" class="form-control" placeholder="Edition" required=""><br>
            <input type="text" name="status" class="form-control" placeholder="Status" required=""><br>
            <input type="text" name="quantity" class="form-control" placeholder="Quantity" required=""><br>
            <input type="text" name="department" class="form-control" placeholder="Department" required=""><br>
            <button style="text-align: center;" class="btn btn-default" type="submit" name="submit">ADD</button>
        </form>
    </div>
</div>
<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "300px";
    document.getElementById("main").style.marginLeft = "300px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    document.body.style.backgroundColor = "white";
}
</script>
</body>
</html>
