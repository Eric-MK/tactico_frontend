<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <!-- Add your CSS styles here -->
</head>
<body>
    <h1>Reset Password</h1>

    <p>Click the link below to reset your password:</p>

    <a href="{{ route('resetPassword', ['token' => $token]) }}">Reset Password</a>

    <p>If you did not request a password reset, please ignore this email.</p>
</body>
</html>
