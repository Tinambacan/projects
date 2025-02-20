<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 50px;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form onsubmit="submitForm(event)">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</body>
<script>
    function submitForm(event) {
        event.preventDefault();
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let xhr = new XMLHttpRequest();

        let formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);
    //    xhr.open('POST', 'https://cybertechlogistic.online/app/controller/controller_rider_login_api.php', true); //

        xhr.open('POST', 'controller/controller_rider_login_api.php', true); //

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response);
                if (response.status === 'success') {
                    console.log('Login successful for user: ' + response.username);
                    console.log('Login successful for user: ' + response.email);
                    console.log('Login successful for user: ' + response.partner_id);
                    localStorage.setItem('partner_id', response.partner_id);
                    // window.location.href = 'assign_delivery.php'; // Adjust the URL accordingly
                } else {
                    console.log("Error Login: " + response.message);
                }
            } else {
                console.log("Error Login");
            }
        }
        xhr.send(formData);
    }
</script>
</html>
