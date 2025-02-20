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
    	<!--_________________sidenav_______________-->
	
	<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

  			<div style="color: white; margin-left: 60px; font-size: 20px;">

                <?php

                    echo "Welcome, ".$_SESSION['employeeid'];  
                ?>
            </div> <br>
            
  <a href="add.php">Add Books</a>
  <a href="request.php">Book Request</a>
  <a href="approve.php">Approved Book Request</a>
  <a href="return.php">Returned Books</a>
  <a href="expire.php">Expired Books Request</a>
  <a href="#">Issue Information</a>
</div>

<div id="main">
  
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
  <h1 style="color:#526E48; font-family:Arial, Helvetica, sans-serif; text-align: center; font-weight: bold;"class="text-center">Returned Books</h1>

  <?php
if (isset($_SESSION['employeeid'])) {
    // Query to fetch student and book details using issue_book
    $query = "
        SELECT 
            s.firstname AS student_firstname,
            s.lastname AS student_lastname,
            b.name AS book_name,
            b.authors AS book_authors,
            b.edition AS book_edition,
            ib.approve, ib.issue, ib.return, ib.issue_id, ib.return
        FROM issue_book ib
        JOIN student s ON s.studentid = ib.studentid
        JOIN books b ON b.bid = ib.bid
        WHERE ib.approve = 'return'
       
    ";
} else {
    // Fallback query to show all books
    $query = "SELECT name AS book_name, authors AS book_authors, edition AS book_edition FROM books ORDER BY name ASC";
}

// Execute the query and store the result
$res = mysqli_query($db, $query);


if (mysqli_num_rows($res) > 0) {
    echo "<table class='table table-bordered table-hover'>";
    echo "<tr>";
    echo "<th>Username</th>";
    echo "<th>Book's Name</th>";
    echo "<th>Authors Name</th>";
    echo "<th>Edition</th>";
    echo "<th>Status</th>";
    echo "<th>Issue Date</th>";
    echo "<th>Return Date</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>";
        if (isset($row['student_firstname']) && isset($row['student_lastname'])) {
            echo "<td>" . $row['student_firstname'] . " " . $row['student_lastname'] . "</td>";
        } else {
            echo "<td>-</td>"; // Fallback if no student information is available
        }
        echo "<td>" . $row['book_name'] . "</td>";
        echo "<td>" . $row['book_authors'] . "</td>";
        echo "<td>" . $row['book_edition'] . "</td>";
        echo "<td>" . $row['approve'] . "</td>";
        echo "<td>" . $row['issue'] . "</td>";
        echo "<td>" . $row['return'] . "</td>";
    
        echo "</tr>";
    }
    echo "</table>";
}else{
    echo "<h1 style='text-align:center; font-weight:bold;'>No Returned Books Found</h1>";

}
?>

<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "300px";
  document.getElementById("main").style.marginLeft = "300px";
  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
  document.body.style.backgroundColor = "white";
}
</script>
</div>
</body>
</html>