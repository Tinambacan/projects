<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Account Credentials Notification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header img {
            width: 150px;
            /* Adjust the width */
            height: auto;
            /* Maintain aspect ratio */
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 22px;
            color: #333333;
        }

        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .content p {
            font-size: 16px;
            color: #555555;
            margin: 10px 0;
        }

        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: #ffffff;
            background-color: #CCAA2C;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button-container a:hover {
            background-color: #CCAA2C;
        }

        .footer {
            font-size: 12px;
            color: #777777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
                    style="background-color: #ffffff; padding: 20px; border-radius: 10px;">
                    <!-- Header -->
                    <tr>
                        <td class="header" style="text-align: center; padding-bottom: 20px;">
                            <img src="{{ URL('images/new-logo.png') }}" style="width: auto; height: 10vh;"
                                alt="Logo">
                            <h2 style="font-size: 18px; font-weight: bold; color: #333333; margin: 0;">
                                Good day, {{ ucwords($adminSalutation) }} {{ ucwords($adminFname) }}
                                {{ ucwords($adminLname) }}!
                            </h2>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td class="content" style="background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
                            <p>The faculty schedules have been updated. Here are the changes:</p>
                            <div class="button-container" style="text-align: center; margin-top: 10px;">
                                <a href="{{ $url }}"
                                    style="display: inline-block; background-color: #CCAA2C; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold; padding: 10px 20px; border-radius: 5px;">
                                    View faculty loads
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="text-align: center; font-size: 12px; color: #777777;  padding: 15px;">
                            <p style="margin: 0;">&copy; {{ date('Y') }} ECRS. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>




{{-- <p>{{ $emailMessage }}</p> --}}
