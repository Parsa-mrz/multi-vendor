<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logged In</title>
</head>

<body>
    <h1>Hello, {{ $email }}</h1>
    <p>You have successfully logged in!</p>

    <h3>Login Details:</h3>
    <ul>
        <li><strong>Login Time:</strong> {{ $loginTime }}</li>
        <li><strong>IP Address:</strong> {{ $ipAddress }}</li>
    </ul>
</body>

</html>