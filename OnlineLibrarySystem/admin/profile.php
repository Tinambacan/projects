<?php
session_start();
include "connection.php";
?>
<!DOCTYPE html>
<html>
<head> 
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
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
        .wrapper {
            width: 300px;
            margin: 0 auto;
            color: black;
        }


    .dashboard-container {
    width: 90%;
    max-width: 800px;
    background-color: #526E48;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin: 0 auto;
    margin-top: 10%;
    }
        .header {
        background-color: #0a8d86;
        color: #ffffff;
        text-align: center;
        padding: 15px 20px;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
        text-align: center;
    }

    .content {
        padding: 20px;
        text-align: center;
        
    }

    .content h2 {
        color: #fff;
        margin-bottom: 5px;
    }

    .content p {
        color: #fff;
        margin-bottom: 20px;
    }

    .details h3 {
        color: #fff;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        color: white;
    }

    table th, table td {
        text-align: left;
        padding: 10px;
        border: 1px solid #62825D;
    }

    table th {
        background-color: #62825D;
        font-weight: bold;
        color: #fff;
    }

    .actions {
        justify-content:center;
        
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        text-align: center;
        color: #ffffff;    
    }

    .btn-update {
        background-color: #62825D;
    }

    .btn-update:hover {
        background-color: #9EDF9C;
    }

    .btn-view {
        background-color: #62825D;
    }

    .btn-view:hover {
        background-color: #9EDF9C;
    }

    .btn-logout {
        background-color: #62825D;
    }

    .btn-logout:hover {
        background-color: #9EDF9C;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body style="background-color: #f4f4f4;">
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
                echo '<li><a href="../login.php"><span class="glyphicon glyphicon-log-in"></span> LOGIN</a></li>';
                echo '<li><a href="registration.php"><span class="glyphicon glyphicon-user"></span> SIGN UP</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>

    <div class="dashboard-container">
        <?php 
        // Fetch student data
        $q = mysqli_query($db, "SELECT firstname, lastname, employeeid, email FROM admin WHERE employeeid='$_SESSION[userid]';");
        $row = mysqli_fetch_assoc($q);
        ?>

        <div class="content">  
            <h2> Welcome, <b> <?php echo $row['employeeid'] ?>! </b>
            </h2> 
            <p>Here is your Profile Details:</p>
            <div class="details">
                <h3>Your Details</h3>
                
                <table > 
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Information</th>
                        </tr>
                    </thead>

                    <tbody >
                        <tr >
                            <td >Admin ID:</td>
                            <td><?php echo  $row['employeeid'] ?></td>
                        </tr>
                        <tr>
                            <td>Last Name:</td>
                            <td><?php echo  $row['lastname'] ?></td>
                        </tr>
                        <tr>
                            <td>First Name:</td>
                            <td><?php echo  $row['firstname'] ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo  $row['email'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-default" style="float: center; width: 70px; background-color:#62825D;" name="submit1" data-toggle="modal" data-target="#editModal">Edit</button>
                <button class="btn btn-default" style="float: center; width: auto; background-color:#62825D;" name="submit2" data-toggle="modal" data-target="#passwordModal">Change Password</button>
            </div>

    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editModalLabel">Edit Student Information</h4>
                </div>
                <div class="modal-body">
                   
                <!-- Student Data -->
                    <form id="editStudentForm">
                        <div class="form-group">
                            <label for="employeeid">Student ID</label>
                            <input type="text" class="form-control" id="employeeid" readonly>
                        </div>
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" >
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Password Change Form -->
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                            <span class="input-group-addon" id="toggleCurrentPassword">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                            <span class="input-group-addon" id="toggleNewPassword">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            <span class="input-group-addon" id="toggleConfirmPassword">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePasswordBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.querySelector('button[name="submit1"]').addEventListener('click', function () {
    const data = {
        firstname: '<?php echo $row['firstname']; ?>',
        lastname: '<?php echo $row['lastname']; ?>',
        employeeid: '<?php echo $row['employeeid']; ?>',
        email: '<?php echo $row['email']; ?>'
    };

    // Update modal fields
    document.getElementById('firstname').value = data.firstname;
    document.getElementById('lastname').value = data.lastname;
    document.getElementById('employeeid').value = data.employeeid;
    document.getElementById('email').value = data.email;
});

// AJAX for submitting changes
document.querySelector('.btn-primary').addEventListener('click', function () {
    const updatedData = {
        firstname: document.getElementById('firstname').value,
        lastname: document.getElementById('lastname').value,
        employeeid: document.getElementById('employeeid').value,
        email: document.getElementById('email').value
    };

    // Send AJAX request
    $.ajax({
        url: 'update_admin.php', // PHP backend script
        type: 'POST',
        data: updatedData,
        success: function (response) {
            // Handle success (e.g., show a success message)
            alert('Student information updated successfully!');
            window.location.reload();
        },
        error: function () {

            alert('An error occurred while updating student information.');
        }
    });
});

</script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    function togglePasswordVisibility(inputFieldId, iconId) {
        var inputField = document.getElementById(inputFieldId);
        var icon = document.getElementById(iconId);
        
        if (inputField.type === "password") {
            inputField.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            inputField.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

    // Add event listeners for toggling password visibility
    document.getElementById('toggleCurrentPassword').addEventListener('click', function () {
        togglePasswordVisibility('currentPassword', 'toggleCurrentPassword').bind(this);
    });
    document.getElementById('toggleNewPassword').addEventListener('click', function () {
        togglePasswordVisibility('newPassword', 'toggleNewPassword').bind(this);
    });
    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        togglePasswordVisibility('confirmPassword', 'toggleConfirmPassword').bind(this);
    });

    // When the "Save Changes" button is clicked
    $('#savePasswordBtn').on('click', function () {
        // Get the form values
        var currentPassword = $('#currentPassword').val();
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        // Simple validation
        if (newPassword !== confirmPassword) {
            alert('New password and confirmation password do not match!');
            return;
        }

        // Send the password change request via Ajax
        $.ajax({
            url: 'change_password.php', // PHP file to handle password change
            type: 'POST',
            data: {
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            success: function (response) {
                // Handle success
                alert(response); // Show the response message from the server
                $('#passwordModal').modal('hide'); // Close the modal
                if (response === 'Password updated successfully!') {
                    // Redirect to index.php after a successful password change
                    window.location.href = 'index.php'; 
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                alert('Error changing password: ' + error);
            }
        });
    });
</script>
</body>
</html>


