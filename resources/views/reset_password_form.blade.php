<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            margin: 0 auto;
            width: 300px;
            text-align: center;
        }

        input[type="email"],
        input[type="password"] {
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

        .alert {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .alert ul {
            margin: 0;
            padding: 0;
        }

        .alert li {
            list-style-type: none;
        }
    </style>
</head>
<body>
    <h1>Reset Password</h1>

    <form action="{{ route('saveResetPassword') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" placeholder="Your Email" required>
        <br><br>
        <input type="password" name="password" placeholder="Enter New Password" required>
        <br><br>
        <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>

    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('success'))
        <div class="alert" style="background-color: #dff0d8; color: #3c763d;">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert">
            {{ Session::get('error') }}
        </div>
    @endif
</body>
</html>
