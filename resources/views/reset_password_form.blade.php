<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <!-- Add your CSS styles here -->
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
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</body>
</html>
