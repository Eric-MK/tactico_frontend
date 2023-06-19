<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verification</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="number"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="submit"],
        #resendOtpVerification {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover,
        #resendOtpVerification:hover {
            background-color: #45a049;
        }

        #message_error,
        #message_success {
            margin-bottom: 10px;
        }

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

        .popup-success {
            color: green;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .popup-message {
            margin-bottom: 20px;
        }

        /* Success message styles */
        #successMessage {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <p id="message_error" style="color:red;"></p>
    <p id="message_success" style="color:green;"></p>
    <form method="post" id="verificationForm">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="number" name="otp" placeholder="Enter OTP" required>
        <br><br>
        <input type="submit" value="Verify">
    </form>

    <p class="time"></p>

    <div id="confirmationPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span id="popupClose" class="popup-close">&times;</span>
            <div class="popup-success">Email Verified</div>
            <div class="popup-message">Your email has been successfully verified.</div>
            <button id="popupConfirm" class="popup-close">OK</button>
        </div>
    </div>

    <button id="resendOtpVerification">Resend Verification OTP</button>

    <div id="successMessage"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#verificationForm').submit(function(e){
                e.preventDefault();
                showConfirmationPopup();
            });

            // Close the popup when clicking the close button
            $('#popupClose').click(function() {
                hideConfirmationPopup();
            });

            // Submit the form when clicking "OK" in the popup
            $('#popupConfirm').click(function() {
                submitForm();
            });
        });

        function showConfirmationPopup() {
            $('#confirmationPopup').fadeIn();
        }

        function hideConfirmationPopup() {
            $('#confirmationPopup').fadeOut();
        }

        function submitForm() {
            var formData = $('#verificationForm').serialize();

            $.ajax({
                url: "{{ route('verifiedOtp') }}",
                type: "POST",
                data: formData,
                success: function(res) {
                    if(res.success){
                        $('#message_success').text(res.msg);
                        setTimeout(function() {
                            $('#message_success').text('');
                        }, 3000);
                        window.open("/", "_self");
                    }
                    else{
                        $('#message_error').text(res.msg);
                        setTimeout(function() {
                            $('#message_error').text('');
                        }, 3000);
                    }
                }
            });

            hideConfirmationPopup();
        }

        function timer() {
            var seconds = 30;
            var minutes = 1;

            var timer = setInterval(function() {
                if(minutes < 0){
                    $('.time').text('');
                    clearInterval(timer);
                }
                else{
                    let tempMinutes = minutes.toString().length > 1? minutes:'0'+minutes;
                    let tempSeconds = seconds.toString().length > 1? seconds:'0'+seconds;

                    $('.time').text(tempMinutes+':'+tempSeconds);
                }

                if(seconds <= 0){
                    minutes--;
                    seconds = 59;
                }

                seconds--;
            }, 1000);
        }

        timer();
    </script>
</body>
</html>
