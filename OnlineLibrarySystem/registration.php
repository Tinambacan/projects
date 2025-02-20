<?php
  include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .navbar-inverse {
        background-color: #526E48;
        border-color: #526E48;
        }

        .navbar-inverse .navbar-brand {
        color: white !important; /* White text for readability */
        cursor: default; /* Indicates it's not a clickable link */
        font-weight: bold; /* Optional: make it stand out */
        }
        
            /* Active Link Styling */
            .navbar-nav li a.active {
        color: white !important; /* Change text color to white */
        }

        section {
            height: 630px;
            width: 1350px;
        }
        .box {
            height: 250px;
            width: 450px;
            background-color: #526E48;
            margin: 60px auto;
            color: white;
            padding: 70px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .radio-group p {
            font-size: 15px;
            font-weight: bold;
            padding-left: 0;
            margin-bottom: 15px;
        }
        .radio-options {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
        }
        .signup-form button {
            width: auto;
            padding: 10px;
            background-color: #62825D;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .signup-form button:hover {
        background-color: #9EDF9C;
    }
        .radio-options {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <span class="navbar-brand active">LibraLink</span>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php">HOME</a></li>
            <li><a href="books.php">BOOKS</a></li>
            <li><a href="feedback.php">FEEDBACK</a></li>
            <?php
            if (isset($_SESSION['login'])) {
                // User is logged in, show PROFILE link
                echo '<li><a href="profile.php">PROFILE</a></li>';
            }
            ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <?php
            if (isset($_SESSION['login'])) {
                // User is logged in
                echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> LOGOUT</a></li>';
            } else {
                // User is not logged in
                echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> LOGIN</a></li>';
                echo '<li><a href="registration.php"><span class="glyphicon glyphicon-user"></span> SIGN UP</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
    <section>
        <div class="box">
            <form name="signup" action="" method="post" class="signup-form">
                <div class="radio-group">
                    <b><p>Sign up as:</p></b>
                    <div class="radio-options">
                        <input type="radio" name="user" id="admin" value="admin">
                        <label for="admin">Admin</label>
                        <input type="radio" name="user" id="student" value="student">
                        <label for="student">Student</label>
                    </div>
                    <button class="btn btn-default" type="submit" name="submit1">Enter</button>
                </div>
            </form>
        </div>
        <?php
            if (isset($_POST['submit1']))
            {
                if($_POST['user']==='admin')
                {
                    echo '<script type="text/javascript">window.location="admin/registration.php";</script>';
                }
                else 
                {
                    echo '<script type="text/javascript">window.location="student/registration.php";</script>';
                }
            }
        
        
        
        ?>
    </section>
</body>