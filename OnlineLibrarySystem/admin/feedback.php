<?php
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="style.css">

    <style>
        .navbar-inverse {
      background-color: #526E48;
      border-color: #526E48;
    }
    .navbar-inverse .navbar-brand {
      color: white !important; /* Always white text */
      cursor: default; /* Indicates it's not a clickable link */
      font-weight: bold; /* Optional: make it stand out */
    }
    .navbar-inverse .navbar-nav > .active > a {
      color: #fff; /* Set active link color */
    }
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .wrapper {
            padding: 40px;
            margin: 30px auto;
            max-width: 800px;
            background-color: #526E48;
            color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            min-height: 400px; /* Increased the height of the wrapper */
        }
        .form-control {
            height: 50px;
            border-radius: 4px;
        }
        .btn-submit {
            width: 100px;
            height: 40px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
        }
        .btn-submit:hover {
            background-color: #fff;
        }
        .scroll {
            width: 100%;
            height: 350px; /* Increased the height of the scroll section */
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
        }
        .scroll table {
            width: 100%;
            border-collapse: collapse;
        }
        .scroll td {
            padding: 10px;
            color: #333;
            border: 2px solid #526E48;
        }
        .scroll tr {
            border: 1px solid #313131;
            text-align: justify;
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
            <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">HOME</a></li>
            <li><a href="books.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'books.php') ? 'active' : ''; ?>">BOOKS</a></li>
            <li><a href="feedback.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'active' : ''; ?>">FEEDBACK</a></li>
            <?php
            if (isset($_SESSION['login'])) {
                // User is logged in, show PROFILE link
                echo '<li><a href="profile.php" class="' . (basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '') . '">PROFILE</a></li>';
                echo '<li class="' . ($_SERVER['PHP_SELF'] == '/student_info.php' ? 'active' : '') . '"><a href="student_info.php">STUDENT INFO</a></li>';
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
    <div class="wrapper">
        <h2 style="color:#fff; font-family:Arial, Helvetica, sans-serif; text-align: center; font-weight: bold;"class="text-center">Student Feedback</h2>
        
        <br><br>
        <div class="scroll">
            <?php
            $q = "SELECT * FROM `comments` ORDER BY `comments`.`id` DESC";
            $res = mysqli_query($db, $q);

            echo "<table>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>";
                echo "<td>"; echo $row['username']; echo "</td>";
                echo "<td>"; echo $row['comment']; echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>
</body>
</html>
