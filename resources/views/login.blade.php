<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* Internal CSS styles for the login form and container */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 20px;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .login-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container a {
            text-decoration: none; /* Remove underline */
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Login</h1>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    @endif

    @if(Session::has('error'))
        <p style="color:red;">{{ Session::get('error') }}</p>
    @endif

    <form action="{{ route('userLogin') }}" method="POST">
        @csrf

        <input type="email" name="email" placeholder="Enter Email">
        <br><br>
        <input type="password" name="password" placeholder="Enter Password">
        <br><br>
        <input type="submit" value="Login">
    </form>

    <p>Not a member? <a href="{{ route('studentRegister') }}">Register</a></p>
</div>
</body>
</html>
