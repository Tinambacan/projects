<?php
    session_start();
  include "connection.php";
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css?v=1.0">
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
    /* STUDENT LOGIN */
    .student-login-container {
        height: 500px;
        width: 450px;
        background-color: #526E48;
        margin: 30px auto;
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

    .student-login-content h1 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .student-login-content p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    .student-login-content input {
        width: 90%;
        padding: 10px;
        margin-bottom: 15px;
        border: none;
        border-radius: 5px;
        background-color: #f4f4f4;
        color: #333;
        font-size: 12px;
    }
    .radio-group p {
    text-align: left;
    padding-left: 15px;
    margin-bottom: 5px;
    display: flex; /* Align radio buttons and label vertically */
    flex-direction: column; /* Stack radio buttons below the label */
    gap: 5px; /* Space between the "Login as:" text and the radio buttons */
    }

    /* Adjust the radio options container to keep buttons beside each other */
    .radio-options {
    padding-right: 77px;
    display: flex; /* Align radio buttons horizontally */
    gap: 15px; /* Adds space between each radio button */
    margin-top: 5px; /* Optional: Add space between the "Login as:" text and the options */
    }

    /* Keep the radio button and label aligned */
    .radio-options input[type="radio"] {
    margin-right: 5px; /* Space between the radio button and label */
    }

    .radio-options label {
    cursor: pointer; /* Makes labels clickable */
    }
    .student-login-content input:focus {
        outline: none;
        border: 2px solid #C2FFC7;
    }

    .student-login-content button {
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

    .student-login-content button:hover {
        background-color: #9EDF9C;
    }

    .student-login-content .forgot-password,
    .student-login-content .signup-link {
        color: #62825D;
        text-decoration: none;
        font-size: 14px;
    }

    .student-login-content .forgot-password:hover,
    .student-login-content .signup-link:hover {
        color: #9EDF9C;
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
    <div class="student-login-container">
        <div class="student-login-content">
            <h1>Welcome to LibraLink</h1>
            <p>Please log in to access your account.</p>
            <form name="login" action="" method="post" class="login-form">
                <div class="radio-group">
                    <b><p>Login as:</p></b>
                    <div class="radio-options">
                        <input type="radio" name="user" id="admin" value="admin">
                        <label for="admin">Admin</label>
                        <input type="radio" name="user" id="student" value="student">
                        <label for="student">Student</label>
                    </div>
                </div>
                <input type="number" name="studentid" id="id-input" placeholder="Student ID" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit" name="login">Login</button>
            </form>
            <a href="forgot_password.php" class="forgot-password">Forgot password?</a><br>
            <p>New to this website? <a href="registration.php" class="signup-link">Sign up</a></p>
        </div>
    </div>

    <script>
        // Change placeholder dynamically based on selected radio button
        document.querySelectorAll('input[name="user"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const idInput = document.getElementById('id-input');
                if (this.value === 'admin') {
                    idInput.placeholder = 'Employee ID';
                } else if (this.value === 'student') {
                    idInput.placeholder = 'Student ID';
                }
            });
        });

        // Client-side validation for radio button
        document.querySelector('.login-form').addEventListener('submit', function (e) {
            const userType = document.querySelector('input[name="user"]:checked');
            if (!userType) {
                e.preventDefault();
                alert('Please select whether you are an Admin or Student.');
            }
        });

        // Prevent validation alerts when "Sign up" link is clicked
        document.querySelector('.signup-link').addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent further propagation of the event
        });
    </script>

    <?php
        if (isset($_POST['login'])) {
            // Get user input
            $userType = isset($_POST['user']) ? $_POST['user'] : null; // Get the selected user type
            $studentid = $_POST['studentid'];
            $password = $_POST['password'];

            if (!$userType) {
                // This condition will only trigger when the form is submitted and user type is not selected
                echo '<script type="text/javascript">alert("Please select whether you are an Admin or Student.");</script>';
                exit();
            }

            // Prepare SQL query based on user type
            if ($userType === 'admin') {
                $stmt = $db->prepare("SELECT * FROM admin WHERE employeeid = ?");
            } else {
                $stmt = $db->prepare("SELECT * FROM student WHERE studentid = ?");
            }

            $stmt->bind_param("s", $studentid);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();

            // Verify password and handle login
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['userid'] = $userType === 'admin' ? $user['employeeid'] : $user['studentid'];
                $_SESSION['usertype'] = $userType;
                $_SESSION['login'] = true;

                // Redirect based on user type
                if ($userType === 'admin') {
                    echo '<script type="text/javascript">window.location="admin/profile.php";</script>';
                } else {
                    echo '<script type="text/javascript">window.location="student/profile.php";</script>';
                }
            } else {
                // Login failed
                echo '<script type="text/javascript">alert("The username and password do not match.");</script>';
            }
        }
    ?>
</body>
</html>
