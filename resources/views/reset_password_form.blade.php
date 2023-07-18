<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">

    <!-- External CSS stylesheets and internal styles for the reset password form -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css">
    <style>
        /* Internal CSS styles for the reset password form */

        /* Styles for the overall body of the page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background-color: #f0f0f0;
        }

        /* Heading style */
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles for the form container */
        form {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Styles for text input fields */
        input[type="email"],
        input[type="password"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.05);
        }

        /* Styles when an input field is focused */
        input[type="email"]:focus,
        input[type="password"]:focus {
            border: 1px solid #4CAF50;
            box-shadow: 0px 0px 5px rgba(76, 175, 80, 0.2);
        }

        /* Styles for the submit button */
        input[type="submit"] {
            width: 300px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        /* Styles when the submit button is hovered */
        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Styles for the alert messages */
        .alert {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Styles for the alert message lists */
        .alert ul {
            margin: 0;
            padding: 0;
        }

        /* Styles for the alert message list items */
        .alert li {
            list-style-type: none;
        }

        /* Styles for the flag select */
        .flag-select select {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Styles for the flag icon */
        .flag-select .flag-icon {
            margin-right: 5px;
        }

        /* Styles to remove the outline on the password input field */
        input[type="password"]:focus {
            outline: none;
        }

        /* Styles for password input indicator */
        input[type="password"].weak-password {
            border: 1px solid red;
        }

        input[type="password"].strong-password {
            border: 2px solid green;
        }
    </style>
</head>
<body>
    <!-- Heading -->
    <h1>Reset Password</h1>

    <!-- Reset password form -->
    <form action="{{ route('saveResetPassword') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email input field -->
        <input type="email" name="email" placeholder="Your Email" required>
        <br><br>

        <!-- Password input field with toggle visibility option -->
        <div class="password-toggle">
            <input type="password" name="password" id="password" placeholder="Enter New Password" autocomplete="new-password" required>
        </div>
        <label for="togglePassword">Show</label>
        <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()">
        <br><br>

        <!-- Confirm password input field -->
        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm New Password" required>
        <br><br>

        <!-- Password strength suggestion message -->
        <p id="password-suggestion" style="color: red;"></p>

        <!-- Submit button -->
        <input type="submit" value="Reset Password">
    </form>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display success message -->
    @if(Session::has('success'))
        <div class="alert" style="background-color: #dff0d8; color: #3c763d;">
            {{ Session::get('success') }}
        </div>
    @endif

    <!-- Display error message -->
    @if(Session::has('error'))
        <div class="alert">
            {{ Session::get('error') }}
        </div>
    @endif

    <!-- JavaScript code for password strength and toggle visibility -->
    <script>
        // Function to update password strength indicator and suggest a strong password
        function updatePasswordStrength() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const passwordLength = password.length;

            // Check password length and set appropriate class for the password input box
            if (passwordLength === 0 || passwordLength < 6) {
                passwordInput.className = 'weak-password';
            } else if (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[!@#$%^&*]/.test(password)) {
                passwordInput.className = 'strong-password';
            } else {
                passwordInput.className = 'weak-password';
            }

            // Check if the passwords match and set appropriate class for the confirm password input box
            if (password === confirmPassword && confirmPassword !== '') {
                confirmPasswordInput.className = 'strong-password';
            } else {
                confirmPasswordInput.className = 'weak-password';
            }

            // Suggest a strong password if it doesn't meet the requirements
            const suggestionElement = document.getElementById('password-suggestion');
            if (passwordLength > 0 && passwordLength < 6) {
                suggestionElement.textContent = 'Password should be at least 6 characters long.';
            } else if (!/[a-z]/.test(password)) {
                suggestionElement.textContent = 'Include at least one lowercase letter.';
            } else if (!/[A-Z]/.test(password)) {
                suggestionElement.textContent = 'Include at least one uppercase letter.';
            } else if (!/[0-9]/.test(password)) {
                suggestionElement.textContent = 'Include at least one number.';
            } else if (!/[!@#$%^&*]/.test(password)) {
                suggestionElement.textContent = 'Include at least one special character (!@#$%^&*).';
            } else {
                suggestionElement.textContent = '';
            }
        }

        // Function to toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const togglePasswordCheckbox = document.getElementById('togglePassword');
            passwordInput.type = togglePasswordCheckbox.checked ? 'text' : 'password';
        }

        // Event listener for password and confirm password inputs
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        passwordInput.addEventListener('input', updatePasswordStrength);
        confirmPasswordInput.addEventListener('input', updatePasswordStrength);
    </script>
</body>
</html>
