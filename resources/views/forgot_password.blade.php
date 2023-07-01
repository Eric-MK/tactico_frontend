<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Add your CSS styles here -->
</head>
<body>
    <h1>Forgot Password</h1>

    @if(Session::has('error'))
        <p style="color:red;">{{ Session::get('error') }}</p>
    @endif

    <form action="{{ route('sendResetLink') }}" method="POST">
        @csrf

        <input type="email" name="email" placeholder="Enter Email" required>
        <br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>
