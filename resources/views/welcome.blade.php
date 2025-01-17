<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reverse reCaptcha</title>

    <!-- Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .container {
            position: relative;
        }
        .recaptcha-box {
            background: #fff;
            border: 1px solid #d3d3d3;
            padding: 10px;
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            width: 340px;
            height: 78px;
        }
        .recaptcha-checkbox {
            position: relative;
            width: 30px;
            height: 30px;
            margin-right: 10px;
            margin-left: 20px;
        }
        .recaptcha-checkbox input[type="checkbox"] {
            position: absolute;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            z-index: 2;
            cursor: pointer;
        }
        .recaptcha-checkbox .spinner, .recaptcha-checkbox .checkmark, .recaptcha-checkbox .cross {
            position: absolute;
            width: 30px;
            height: 30px;
            top: 0;
            left: 0;
            border: 2px solid #d3d3d3;
            border-radius: 3px;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .recaptcha-checkbox .spinner {
            border-top-color: #4285f4;
            border-left-color: #4285f4;
            border-bottom-color: transparent;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }
        .recaptcha-checkbox .checkmark {
            color: green;
            font-size: 20px;
            display: none;
        }
        .recaptcha-checkbox .cross {
            color: red;
            font-size: 20px;
            display: none;
        }
        .recaptcha-label {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 0;
        }
        .recaptcha-label p {
            margin: 0;
            font-size: 14px;
        }
        .recaptcha-label .bold-text {
            font-weight: bold;
        }
        .recaptcha-branding {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 110px;
        }
        .recaptcha-branding img {
            width: 40px;
            height: 40px;
        }
        .recaptcha-branding small {
            font-size: 10px;
            color: #999;
        }
        .reverse-recaptcha-text {
            font-size: 11px;
            color: #999;
            position: absolute;
            bottom: 15px;
            right: 10px;
        }
        .privacy-terms-text {
            font-size: 10px;
            color: #999;
            position: absolute;
            bottom: 5px;
            right: 30px;
        }
        .notification {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 10px;
            padding: 10px;
            background-color: #EC5163;
            border: 1px solid #ffcccc;
            border-radius: 3px;
            color: white;
            font-size: 12px;
            width: 340px;
            text-align: center;
            opacity: 0;
            display: none;
        }
        .notification.show {
            display: block;
            animation: fadeIn 1s forwards;
        }
        .notification a {
            color: white;
            text-decoration: underline;
        }
        .notification a:hover {
            color: black;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recaptcha-box">
            <div class="recaptcha-checkbox">
                <div class="spinner"></div>
                <div class="checkmark">✔</div>
                <div class="cross">✖</div>
                <input type="checkbox" id="recaptcha-checkbox" name="recaptcha-checkbox">
            </div>
            <div class="recaptcha-label">
                <p>I'm not a human</p>
            </div>
            <div class="recaptcha-branding">
                <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCaptcha Logo">
            </div>
            <div class="reverse-recaptcha-text">Reverse reCAPTCHA</div>
            <div class="privacy-terms-text">Privacy - Terms</div>
        </div>
        <div class="notification" id="notification">
            You have failed the Reverse reCAPTCHA, you must be human. If you are a robot click <a href="javascript:location.reload()">here</a> to reload the page to try again.
        </div>
    </div>
    <form id="recaptcha-form" action="/validate-recaptcha" method="POST" style="display:none;">
        @csrf
        <input type="checkbox" id="recaptcha-checkbox-hidden" name="recaptcha-checkbox">
    </form>

    <script>
        let isBot = true;
        let startX, startY, endX, endY;
        let automatedEvent = false;

        function handleMouseMove(event) {
            console.log('Mouse move detected');
            if (!startX || !startY) {
                startX = event.clientX;
                startY = event.clientY;
                console.log(`Start: (${startX}, ${startY})`);
            } else {
                endX = event.clientX;
                endY = event.clientY;
                console.log(`End: (${endX}, ${endY})`);
                if (Math.abs(startX - endX) > 5 || Math.abs(startY - endY) > 5) {
                    isBot = false;
                    console.log('Not a straight line');
                }
            }
        }

        document.addEventListener('mousemove', function(event) {
            if (!automatedEvent) {
                handleMouseMove(event);
            }
        });

        document.getElementById('recaptcha-checkbox').addEventListener('change', function(event) {
            console.log('Checkbox changed');
            const checkbox = event.target;
            const hiddenCheckbox = document.getElementById('recaptcha-checkbox-hidden');
            const spinner = document.querySelector('.spinner');
            const checkmark = document.querySelector('.checkmark');
            const cross = document.querySelector('.cross');
            const notification = document.getElementById('notification');

            spinner.style.display = 'block';
            checkbox.style.display = 'none';

            setTimeout(() => {
                spinner.style.display = 'none';
                if (isBot) {
                    checkmark.style.display = 'flex';
                    hiddenCheckbox.checked = true;
                    setTimeout(() => {
                        document.getElementById('recaptcha-form').submit();
                        console.log('Form submitted by bot');
                    }, 1000);
                } else {
                    cross.style.display = 'flex';
                    notification.classList.add('show');
                    console.log('Failed reCaptcha: You\'re not a bot!');
                }
            }, 1000);
        });


        // Automated checkbox checking script for testing, uncomment the code below with "ctrl + /" to use the script to unlock the reverse captcha
        // !!! Beware, even with the script, if it detects mouse movement
        //     the captcha will fail, there must be no user input and the captcha must be completed by a bot or a script ONLY to pass !!!


        // function simulateBotActions() {
        //     const checkbox = document.getElementById('recaptcha-checkbox');
        //     checkbox.checked = true;
        //     console.log('Checkbox checked by bot');
        //     checkbox.dispatchEvent(new Event('change'));
        // }
        // window.onload = function() {
        //     simulateBotActions();
        // };

    </script>
</body>
</html>
