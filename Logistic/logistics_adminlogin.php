<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="css/logistics_adminlogin.css">
    </head>
    <body>
        <div class="login-container">
            <img class="background-image" 
                src="images/logisticslogo.png" 
                alt="Background Image">
            <div class="content">
                <h2>Welcome Back!</h2>
                <form onsubmit="submitForm(event)">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/logistics_adminlogin.js"></script>
</html>
