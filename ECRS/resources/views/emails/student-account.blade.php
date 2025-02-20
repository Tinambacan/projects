<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Account Credentials Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            /* Center align content */
        }

        .header {
            padding: 1rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 10zpx;
            background-color: #ffffff;
            border-radius: 10px;
        }

        .content-body {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            text-align: center;
            margin-top: 1rem;
        }

        .button-container a {
            text-decoration: none;
        }


        .button-link {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            background-color: #CCAA2C;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s;
        }


        .button-link:hover {
            background-color: #CCAA2C;
            /* Darker shade on hover */
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ URL('images/logo-bg.png') }}" alt="Logo"
                style="width: auto; height: 10vh; margin-bottom: 20px;">
            <h2>Good day, {{ ucwords($fname) }} {{ ucwords($mname) }} {{ ucwords($lname) }}!</h2>
        </div>
        <div class="content">
            <div class="content-body">
                <p>This is your student number <strong>{{ $studentno }}</strong></p>
                <p>This is your temporary password <strong>{{ $plainPassword }}</strong></p>
                <strong>Please change your password immediately for your account security.</strong>
                <p>Click the button below to login</p>
            </div>
            <div class="button-container">
                <a href="{{ $url }}" class="button-link">Login</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ECRS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
