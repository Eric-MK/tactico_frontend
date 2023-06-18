<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"
/>
    <style>
        /* Internal CSS styles for the register form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            color: ;
        }

        .success-message {
            color: green;
        }

        .login-link {
            margin-top: 10px;
            text-align: center;
        }

        .login-link a {
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: ;
        }

        /* Password strength indicator styles */
        .password-strength {
            text-align: left;
            margin-bottom: 10px;
        }

        .password-strength span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 5px;
            border-radius: 50%;
        }

        .password-strength .weak {
            background-color: red;
        }

        .password-strength .strong {
            background-color: green;
        }

        /* Flag select styles */
        .flag-select select {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .flag-select .flag-icon {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <h1>Register</h1>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

    @if(Session::has('success'))
        <p class="success-message">{{ Session::get('success') }}</p>
    @endif

    <form action="{{ route('studentRegister') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Enter Name" required>
        <br><br>
        <input type="email" name="email" placeholder="Enter Email" required>
        <br><br>
        <select name="country_code" required>
            <option value="" selected disabled>Select Country</option>
            <option value="+1" data-content="<span class='fi fi-us'></span> United States" class="flag-icon">United States</option>
            <option value="+30" data-content="<span class='fi fi-gr'></span> Greece" class="flag-icon">Greece</option>
            <!-- Add more countries and their respective options here -->
        </select>




        <input type="text" name="phone_number" placeholder="Enter Phone Number" required>
        <br><span class="fi fi-us"></span> <span class="fi fi-gr"></span><br>
        <span class="password-strength">
            <span id="password-strength-indicator"></span>
        </span>
        <input type="password" name="password" id="password" placeholder="Enter Password" required>
        <br><br>
        <input type="password" name="password_confirmation" placeholder="Enter Confirm Password" required>
        <br><br>
        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        <p>Already a member? <a href="{{ route('userLogin') }}">Login</a></p>
    </div>

    <script>
        // Function to update password strength indicator
        function updatePasswordStrength() {
            const passwordInput = document.getElementById('password');
            const passwordIndicator = document.getElementById('password-strength-indicator');

            // Password strength criteria
            const passwordCriteria = {
                weak: /^.{0,5}$/, // Less than 6 characters
                strong: /^.{6,}$/ // 6 or more characters
            };

            // Check password against each strength criteria
            for (const strength in passwordCriteria) {
                if (passwordCriteria[strength].test(passwordInput.value)) {
                    passwordIndicator.style.backgroundColor = strength === 'strong' ? 'green' : 'red';
                    return;
                }
            }
        }

        // Event listener for password input
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', updatePasswordStrength);
    </script>
</body>
</html>
