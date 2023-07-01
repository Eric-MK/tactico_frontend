<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
        color: #333;
        text-align: center;
    }

    form {
        margin: 0 auto;
        width: 300px;
        text-align: center;
    }

    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error-message {
        color: red;
        text-align: center;
    }

    .success-message {
            color: green;
            text-align: center;
        }
</style>
</head>
<body>
    <h1>Forgot Password</h1>

    @if(Session::has('error'))
        <p class="error-message">{{ Session::get('error') }}</p>
    @endif

    @if(Session::has('success'))
        <p class="success-message">{{ Session::get('success') }}</p>
    @endif

    <form action="{{ route('sendResetLink') }}" method="POST">
        @csrf

        <input type="email" name="email" placeholder="Enter Email" required>
        <br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
