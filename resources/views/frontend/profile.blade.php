<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .main-body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="main-body">

    @include('frontend.Navigation') <!-- Include the navigation view -->

<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Profile') }}</div>
                @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <div class="card-body">
                    <form id="updateProfileForm" method="POST" action="/profile">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}"  autocomplete="name" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"  autocomplete="email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}"  autocomplete="phone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" id="password" autocomplete="new-password">
                                <input type="checkbox" id="toggle-password"> Show Password
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Profile') }}
                                </button>

                            </div>
                        </div>
                    </form>

<form method="POST" id="deleteAccountForm" action="{{ route('deleteAccount', ['user' => Auth::user()->id]) }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
    @csrf
    <button type="submit" class="btn btn-danger">
        {{ __('Delete Account') }}
    </button>
</form> <!-- end of the delete account form -->
                </div>
            </div>
        </div>
    </div>
</div>

<br>
@include('frontend.FooterPage') <!-- Include the footer view -->


<script>
  document.querySelector('#updateProfileForm').addEventListener('submit', function(e) {
 // Get the phone number
 var phone = document.querySelector('#phone').value;

// Original phone from hidden input
var originalPhone = '{{ Auth::user()->phone }}';

// Check if the phone number has changed
if (phone !== originalPhone) {
    // If changed, check if it's a Kenyan number (9 or 10 digits, starts with '07' or '7', or starts with '+254')
    var kenyanPhoneRegex = /^(?:\+254|0)?[17]\d{8}$/;
    if (!kenyanPhoneRegex.test(phone)) {
        swal("Error", "Invalid Kenyan phone number. Please enter a valid number.", "error");
        e.preventDefault();
        return;
    }
}

    // Get the password and password confirm inputs
    var password = document.querySelector('#password').value;
    var passwordConfirm = document.querySelector('#password-confirm').value;

    // Check if the passwords are not empty
    if (password.length > 0 || passwordConfirm.length > 0) {
        // Check if the passwords match and are at least 6 characters long
        if (password !== passwordConfirm) {
            swal("Error", "Passwords do not match.", "error");
            e.preventDefault();
            return;
        } else if (password.length < 6) {
            swal("Error", "Password should be at least 6 characters long.", "error");
            e.preventDefault();
            return;
        }
    }

    // Original email from hidden input
    var originalEmail = '{{ Auth::user()->email }}';
    // New email from email input field
    var newEmail = document.querySelector('#email').value;

    // Message to display when email changes
    var message = 'Are you sure you want to submit your data?';
    if (originalEmail !== newEmail) {
        message += ' If you change your email, you will be logged out.';
    }

    // Confirm submission
    e.preventDefault(); // Always prevent the default first

    swal({
        title: "Confirm Submission",
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "green",
        confirmButtonText: "Yes, I'm sure!",
        closeOnConfirm: false
    }, function() {
        e.target.submit(); // Only submit the form in the callback when the user has confirmed
    });
});



    document.getElementById('toggle-password').addEventListener('change', function (e) {
    var password = document.getElementById('password');
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
});

document.getElementById('toggle-confirm-password').addEventListener('change', function (e) {
    var passwordConfirm = document.getElementById('password-confirm');
    if (passwordConfirm.type === "password") {
        passwordConfirm.type = "text";
    } else {
        passwordConfirm.type = "password";
    }
});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
