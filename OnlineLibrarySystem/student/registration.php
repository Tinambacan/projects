<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
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
        /* REGISTRATION */
        .student-registration-container {
            height: 550px;
            width: 450px;
            background-color: #526E48;
            margin: 25px auto;
            color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .student-registration-content h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .student-registration-content p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .student-registration-content input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: #f4f4f4;
            color: #333;
            font-size: 12px;
        }

        .student-registration-content input:focus {
            outline: none;
            border: 2px solid #C2FFC7;
        }

        .student-registration-content button {
            width: auto;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #62825D;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .student-registration-content button:hover {
            background-color: #9EDF9C;
        }

        .student-registration-content .forgot-password,
        .student-registration-content .signup-link {
            color: #62825D;
            text-decoration: none;
            font-size: 14px;
        }

        .student-registration-content .forgot-password:hover,
        .student-registration-content .signup-link:hover {
            color: #9EDF9C;
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
                // User is logged in, show PROFILE link
                echo '<li class="' . ($_SERVER['PHP_SELF'] == '/profile.php' ? 'active' : '') . '"><a href="profile.php">PROFILE</a></li>';
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

      <div class="student-registration-container">
        <div class="student-registration-content">
            <h1>Welcome to LibraLink</h1>
            <p>User Registration Form</p>
            <form name="registration" action="" method="post">
                <input type="text" name="firstname" placeholder="First Name" required><br>
                <input type="text" name="lastname" placeholder="Last Name" required><br>
                <input type="number" name="studentid" placeholder="Student ID" required><br>
                <input type="text" name="email" placeholder="Email" required><br>
                <input type="password" name="password" id="password" placeholder="Password" required><br>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required><br>
                <button type="submit" name="submit">Sign up</button>
            </form>
            <p>Have an account? <a href="../login.php" class="signup-link">Login</a></p>
        </div>
    </div>

    <?php
        include "connection.php"; // Ensure $db is defined in this file

        if (isset($_POST['submit'])) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $studentid = $_POST['studentid'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Check for duplicate student ID
            $checkQuery = "SELECT * FROM STUDENT WHERE studentid = '$studentid'";
            $checkResult = mysqli_query($db, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                echo "<p>Error: Student ID already exists!</p>";
            } elseif ($password !== $confirmPassword) {
                echo "<p>Error: Passwords do not match!</p>";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert the new record
                $insertQuery = "INSERT INTO STUDENT (firstname, lastname, studentid, email, password) 
                                VALUES ('$firstname', '$lastname', '$studentid', '$email', '$hashedPassword')";
                if (mysqli_query($db, $insertQuery)) {
                    echo '<script type="text/javascript">window.location="../login.php";</script>';
                } else {
                    echo "<p>Error: " . mysqli_error($db) . "</p>";
                }
            }
        }
    ?>

</body>
</html>