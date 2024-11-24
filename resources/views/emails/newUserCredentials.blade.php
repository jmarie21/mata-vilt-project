<!DOCTYPE html>
<html>
<head>
    <title>Your New Account Credentials</title>
</head>
<body>
    <h1>Hello, {{ $data['name'] }}</h1>
    <p>Your account has been created. Here are your login credentials:</p>
    
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Password:</strong> {{ $data['password'] }}</p>

    <p>Please make sure to change your password after logging in for the first time.</p>

    <p>Best regards,<br>Your Team</p>
</body>
</html>
