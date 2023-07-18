<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Page title and favicon -->
    <title>Verification</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">

    <!-- Internal CSS styles -->
    <style>
        /* Styles for the overall body of the page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        /* Heading style */
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles for the form container */
        form {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles for the number input field */
        input[type="number"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Styles for the submit button and resend OTP button */
        input[type="submit"],
        #resendOtpVerification {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        /* Styles when the submit button or resend OTP button is hovered */
        input[type="submit"]:hover,
        #resendOtpVerification:hover {
            background-color: #45a049;
        }

        /* Styles for the success and error messages */
        #message_error,
        #message_success {
            margin-bottom: 10px;
        }

        /* Styles for the timer */
        .time {
            font-size: 18px;
            margin-bottom: 10px;
        }

        /* Popup styles */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            color: #888;
            cursor: pointer;
        }

        .popup-message {
            margin-bottom: 20px;
        }

        .popup-confirm {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .popup-confirm:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Error and success messages -->
    <p id="message_error" style="color:red;"></p>
    <p id="message_success" style="color:green;"></p>

    <!-- Verification form -->
    <form method="post" id="verificationForm">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="number" name="otp" placeholder="Enter OTP" required>
        <br><br>
        <input type="submit" value="Verify">
    </form>

    <!-- Timer -->
    <p class="time"></p>

    <!-- Resend OTP button -->
    <button id="resendOtpVerification">Resend Verification OTP</button>

    <!-- Confirmation popup -->
    <div id="confirmationPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span id="popupClose" class="popup-close">&times;</span>
            <div class="popup-message" id="popupMessage"></div>
            <button id="popupConfirm" class="popup-confirm">OK</button>
        </div>
    </div>

    <!-- JavaScript code using jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            // Handle form submission
            $('#verificationForm').submit(function(e){
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url:"{{ route('verifiedOtp') }}",
                    type:"POST",
                    data: formData,
                    success:function(res){
                        if(res.success){
                            showPopup(res.msg);
                            setTimeout(function() {
                                window.open("/","_self");
                            }, 5000);
                        }
                        else{
                            $('#message_error').text(res.msg);
                            setTimeout(() => {
                                $('#message_error').text('');
                            }, 3000);
                        }
                    }
                });
            });

            // Handle resend OTP button click
            $('#resendOtpVerification').click(function(){
                $(this).text('Wait...');
                var userMail = @json($email);

                $.ajax({
                    url:"{{ route('resendOtp') }}",
                    type:"GET",
                    data: {email:userMail },
                    success:function(res){
                        $('#resendOtpVerification').text('Resend Verification OTP');
                        if(res.success){
                            timer();
                            $('#message_success').text(res.msg);
                            setTimeout(() => {
                                $('#message_success').text('');
                            }, 3000);
                        }
                        else{
                            $('#message_error').text(res.msg);
                            setTimeout(() => {
                                $('#message_error').text('');
                            }, 3000);
                        }
                    }
                });
            });

            // Close the popup when clicking the close button
            $(document).on('click', '.popup-close', function() {
                hidePopup();
            });

            // Submit the form when clicking "OK" in the popup
            $(document).on('click', '#popupConfirm', function() {
                submitForm();
            });
        });

        // Function to show the popup
        function showPopup(message) {
            $('#popupMessage').text(message);
            $('#confirmationPopup').fadeIn();
        }

        // Function to hide the popup
        function hidePopup() {
            $('#confirmationPopup').fadeOut();
        }

        // Function to submit the form
        function submitForm() {
            var formData = $('#verificationForm').serialize();

            $.ajax({
                url: "{{ route('verifiedOtp') }}",
                type: "POST",
                data: formData,
                success: function(res) {
                    if(res.success){
                        $('#message_success').text(res.msg);
                        setTimeout(() => {
                            $('#message_success').text('');
                        }, 5000);
                        window.open("/", "_self");
                    }
                    else{
                        $('#message_error').text(res.msg);
                        setTimeout(() => {
                            $('#message_error').text('');
                        }, 3000);
                    }
                }
            });

            hidePopup();
        }

        // Function for the timer
        function timer() {
    // Initialize variables for seconds and minutes
    var seconds = 30;
    var minutes = 1;

    // Set up a timer interval that executes every 1000 milliseconds (1 second)
    var timer = setInterval(() => {
        // Check if minutes is less than 0, indicating the timer has reached zero
        if (minutes < 0) {
            // Clear the interval to stop the timer
            $('.time').text('');
            clearInterval(timer);
        } else {
            // Format the minutes and seconds with leading zeros if necessary
            let tempMinutes = minutes.toString().length > 1 ? minutes : '0' + minutes;
            let tempSeconds = seconds.toString().length > 1 ? seconds : '0' + seconds;

            // Update the text content of elements with the class .time to display the formatted time
            $('.time').text(tempMinutes + ':' + tempSeconds);
        }

        // Check if seconds is less than or equal to 0, indicating one minute has passed
        if (seconds <= 0) {
            // Decrement the minutes by 1 and reset the seconds to 59 for the next minute
            minutes--;
            seconds = 59;
        }

        // Decrement the seconds by 1 for the current minute
        seconds--;
    }, 1000);
}

// Start the timer by calling the timer function
timer();
    </script>
</body>
</html>
