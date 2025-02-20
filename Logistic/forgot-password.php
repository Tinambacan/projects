<?php
$email = $_GET['email'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="./css/forgot-password.css">
	<title>Forgot Password</title>
</head>
<body>
<section class="vh-100" style="position: relative;">
  
  <!-- Rest of the section content -->
  <div class="container">
    <div class="card">
      <div class="card-body">
        <form id="forgotform">
          <h5 class="fw-normal pb-2 text-center" style="letter-spacing: 1px;">Password Reset</h5>
          <div class="form-outline">
            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter Email Address" readonly value="<?php echo $email;?>">
          </div>
          <div class="position-relative">
            <label for="password" class="form-label">New Password</label>
            <span class="text-danger">*</span>
            <div class="md-form input-group">
              <input type="password" id="password_reg" class="form-control" name="password_reg" placeholder="New Password" required>
              <button class="input-group-text" type="button" id="togglePassword"><i class="fa fa-eye-slash"></i></button>
            </div>
          </div>
          <div class="position-relative">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <span class="text-danger">*</span>
            <div class="md-form input-group">
              <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
              <button class="input-group-text" type="button" id="toggleConfirmPassword"><i class="fa fa-eye-slash"></i></button>
            </div>
          </div>
          <div id="passwordMismatch" class="alert alert-danger">Passwords do not match</div>
          <div id="errorMessage" role="alert"></div>
          <div class="text-center">
            <button class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script src="js/forgot-password.js"></script>

</body>
</html>