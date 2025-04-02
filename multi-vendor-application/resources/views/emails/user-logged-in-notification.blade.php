<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logged In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            font-size: 14px;
            padding: 5px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Hello, {{ $email }}</h1>
    <p>You have successfully logged in!</p>
    <h3>Login Details:</h3>
    <ul>
        <li><strong>Login Time:</strong> {{ $loginTime }}</li>
        <li><strong>IP Address:</strong> {{ $ipAddress }}</li>
    </ul>
    <div class="footer">If you did not log in, please secure your account immediately.</div>
</div>
</body>

</html>
