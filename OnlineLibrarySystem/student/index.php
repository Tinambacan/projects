<?php
  session_start(); // Start session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bowlby+One&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <title>Online Library System</title>
  
  <style>
    body {
      background-image: url('images/bg.png'); /* Use url() to specify the image path */
      background-size: cover; /* Ensure the image covers the entire background */
      background-repeat: no-repeat; /* Prevent the image from repeating */
      background-attachment: fixed; /* Keep the background fixed during scrolling */
      background-position: center; /* Center the image */
    }

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

    /* Main Content Styling */
    .main-content {
      color: #fff;
      text-align: left;
      margin-top: 100px;
      padding: 60px;
    }
    
    .main-content h1 {
      font-family: 'Bowlby One', cursive; /* Apply Bowlby One font */
      font-size: 50px;
      margin-bottom: 20px;
    }

    .main-content p {
      font-size: 15px;
      margin-bottom: 1px;
    }
    
    /* Footer Styling */
    footer {
      background-color: #526E48;
      color: white; /* White text for readability */
      text-align: center;
      padding: 5px;
      position: fixed;
      width: 100%;
      bottom: 0;
    }
    
    footer p {
      font-size: 16px;
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

  <!-- Main Content Section -->
  <div class="main-content">
    <h1>Welcome to LibraLink</h1>
    <p>Your gateway to a world of books and knowledge. Discover, borrow, and enjoy your favorite reads.</p>
    <p>Explore our collection and get lost in the world of books.</p>
  </div>

  <!-- Footer Section -->
  <footer>
    <p>Contact us: libralink@gmail.com | Phone: +123 456 7890</p>
  </footer>

</body>
</html>
