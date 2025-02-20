<?php
include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
            border: 1px solid #526E48;
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
    <div class="container">
        <!-- SEARCH BAR -->
        <div class="search-container">
            <form method="post" name="form1">
                <input type="text" name="search" placeholder="Search student">
                <button type="submit" name="submit">
                    <span class="glyphicon glyphicon-search"></span> Search
                </button>
            </form>
        </div>

        <h2 style="color:#526E48; font-family:Arial, Helvetica, sans-serif; text-align: center; font-weight: bold;"class="text-center">List of Students</h2>
        <div class="table-container">
            <?php
            if (isset($_POST['submit'])) {
                $search = mysqli_real_escape_string($db, $_POST['search']);
                $query = "SELECT firstname, lastname, studentid, email 
                          FROM `student` 
                          WHERE `firstname` LIKE '%$search%' OR `lastname` LIKE '%$search%' 
                          ORDER BY `firstname` ASC";
            } else {
                $query = "SELECT firstname, lastname, studentid, email 
                          FROM `student` 
                          ORDER BY `firstname` ASC";
            }

            $res = mysqli_query($db, $query);

            if (mysqli_num_rows($res) > 0) {
                echo "<table class='table table-bordered table-hover'>";
                echo "<tr>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th>Student ID</th>";
                echo "<th>Email</th>";
                echo "</tr>";

                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>";
                    echo "<td>{$row['firstname']}</td>";
                    echo "<td>{$row['lastname']}</td>";
                    echo "<td>{$row['studentid']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='text-center text-muted'>No students found matching your search criteria.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
