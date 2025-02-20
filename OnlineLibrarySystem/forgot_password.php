<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Forgot Password Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="student-registration-container">
        <div class="student-registration-content">
            <h1>Welcome to LibraLink</h1>
            <p>Student Forgot Password Form</p>
            <form name="forgot_password" action="" method="post">
                <input type="number" name="studentid" placeholder="Student ID" required><br>
                <input type="text" name="email" placeholder="Email" required><br>
                <br>
                <input type="password" name="newPassword" id="password" placeholder="New Password" required><br>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm New Password"
                    required><br>
                <button type="submit" name="forgot">Save New Password</button>
            </form>
            <p>Have an acount? <a href="../login.php" class="signup-link">Login</a></p>
        </div>
    </div>

    <?php
    include "connection.php"; // Ensure $db is defined in this file
    
    if (isset($_POST['forgot'])) {
        $studentid = $_POST['studentid'];
        $email = $_POST['email'];
        $password = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Check for if student ID exists
        $checkQuery = "SELECT * FROM student WHERE studentid = '$studentid' AND email='$email'";
        $checkResult = mysqli_query($db, $checkQuery);

        if ($password !== $confirmPassword) {
            echo "<p>Error: Passwords do not match!</p>";
        } elseif (mysqli_num_rows($checkResult) > 0) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Upadte record
            $updateQuery = "UPDATE student SET password='$hashedPassword' WHERE studentid='$studentid' AND email='$email'";
            if (mysqli_query($db, $updateQuery)) {
                echo '<script type="text/javascript">window.location="../login.php";</script>';
            } else {
                echo "<p>Error: " . mysqli_error($db) . "</p>";
            }
        } else {
            echo "<p>Error: Student with this Student ID and this Email does not exists!</p>";
        }
    }
    ?>

</body>

</html>