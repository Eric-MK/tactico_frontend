<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">

    <style>
        /* Internal CSS styles for the login form and container */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }

        .login-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
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
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.05);
        }

        .login-container input[type="email"]:focus,
        .login-container input[type="password"]:focus {
            border: 1px solid #4CAF50;
            box-shadow: 0px 0px 5px rgba(76, 175, 80, 0.2);
        }

        .login-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #724caf;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
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




    <p><a href="{{ route('forgotPassword') }}">Forgot Password?</a></p>
</div>
</body>
</html>
