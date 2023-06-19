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
        input[type="password"]{
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

        input[type="password"]:focus {
    outline: none;
}

        /* Password input indicator styles */
       /* Password input indicator styles */
input[type="password"].weak-password {
    border-color: red !important;
}

input[type="password"].strong-password {
    border-color: green !important;
}

/* Popup styles */
.popup-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.popup-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 4px;
    text-align: center;
}

.popup-buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.cancel-button {
    background-color: #aaa;
    color: #fff;
    padding: 8px 16px;
    margin-right: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.confirm-button {
    background-color: #4CAF50;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
    </style>
</head>
<body>
    <h1>Register</h1>

    @if($errors->any())
        @foreach($errors->all() as $error)
        <p style="color: red;">{{ $error }}</p>
        @endforeach
    @endif

    @if(Session::has('success'))
        <p class="success-message">{{ Session::get('success') }}</p>
    @endif

    <form id="registerForm" action="{{ route('studentRegister') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Enter Name" required>
        <br><br>
        <input type="email" name="email" placeholder="Enter Email" required>
        <br><br>
        {{-- <select name="country_code" required>
            <option value="" selected disabled>Select Country</option>
            <option value="+1" data-content="<span class='fi fi-us'></span> United States" class="flag-icon">United States</option>
            <option value="+30" data-content="<span class='fi fi-gr'></span> Greece" class="flag-icon">Greece</option>
            <!-- Add more countries and their respective options here -->
        </select> --}}
        <input type="text" name="phone" placeholder="Enter Phone Number" required>
        <br><span class="fi fi-us"></span> <span class="fi fi-gr"></span><br>
        <input type="password" name="password" id="password" placeholder="Enter Password" required>
        <br><br>
        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Enter Confirm Password" required>
        <br><br>
        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        <p>Already a member? <a href="{{ route('userLogin') }}">Login</a></p>
    </div>

    <!-- Confirmation Popup -->
    <div id="confirmationPopup" class="popup-container">
        <div class="popup-content">
            <p>Are you sure you want to submit the details?</p>
            <div class="popup-buttons">
                <button id="cancelButton" class="cancel-button">Cancel</button>
                <button id="confirmButton" class="confirm-button">OK</button>
            </div>
        </div>
    </div>

    <script>
     // Function to update password input indicator
function updatePasswordStrength() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const passwordLength = password.length;

    // Check password length and set appropriate class for the password input box
    if (passwordLength === 0 || passwordLength <= 5) {
        passwordInput.className = 'weak-password';
    } else {
        passwordInput.className = 'strong-password';
    }

    // Check if the passwords match and set appropriate class for the confirm password input box
    if (password === confirmPassword && confirmPassword !== '') {
        confirmPasswordInput.className = 'strong-password';
    } else {
        confirmPasswordInput.className = 'weak-password';
    }
}

// Event listener for password and confirm password inputs
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmPassword');
passwordInput.addEventListener('input', updatePasswordStrength);
confirmPasswordInput.addEventListener('input', updatePasswordStrength);

// Function to show confirmation popup
function showConfirmationPopup(event) {
    event.preventDefault();

    const confirmationPopup = document.getElementById('confirmationPopup');
    confirmationPopup.style.display = 'flex';

    const cancelButton = document.getElementById('cancelButton');
    cancelButton.addEventListener('click', hideConfirmationPopup);

    const confirmButton = document.getElementById('confirmButton');
    confirmButton.addEventListener('click', submitForm);
}

// Function to hide confirmation popup
function hideConfirmationPopup() {
    const confirmationPopup = document.getElementById('confirmationPopup');
    confirmationPopup.style.display = 'none';
}

// Function to submit the form
function submitForm() {
    const form = document.getElementById('registerForm');
    form.submit();
}

// Event listener for form submission
const form = document.getElementById('registerForm');
form.addEventListener('submit', showConfirmationPopup);

    </script>
</body>
</html>
