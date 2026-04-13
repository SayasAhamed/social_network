<?php
// Start session at the very beginning of the script
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Social Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            background-image: url('images/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            overflow-x: hidden;
            font-family: 'Arial', sans-serif;
        }

        /* Fade-in and Slide-in animations */
        @keyframes fadeInUp {
                0% { opacity: 0; transform: translateY(30px); }
                100% { opacity: 1; transform: translateY(0); }
        }

         @keyframes slideInLeft {
                0% { opacity: 0; transform: translateX(-100px); }
                100% { opacity: 1; transform: translateX(0); }
        }
        


        .container {
            margin-top: 100px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light background */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-in-out;
        }
        h1 {
            font-family: 'Arial', sans-serif;
            color: #636eff;
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
            font-size:50px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        h3 {
            color: #333;
        }
        .instructions {
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 16px;
            color: #555;
        }
        .contact-email {
            color: #187FAB;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }
        .footer a {
            color: #187FAB;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            color: #00de43;
        }
        .header {
            text-align: center;
            background-color: #187FAB;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .animated-text {
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .button-contact {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00de43;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .button-contact:hover {
            background-color: #187FAB;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Contact Us</h1>
        </div>

        <div class="instructions animated-text">
            <h3>Welcome to Social Hub's Contact Page</h3>
            <p>If you're having trouble logging in or signing up, please don't hesitate to reach out to us. We are here to help you.</p>
            <p>If you're unable to log in or sign up, you can contact us through the email address below:</p>
            <p><span class="contact-email">support@socialhub.com</span></p>

            <p>In case you've forgotten your password, please use the "Forgot Password" option on the login page. You'll be asked to answer a few security questions to verify your identity. Once verified, we will help you reset your password and regain access to your account.</p>

            <p>Social Hub is your social media platform to connect with the world. Whether you're sharing your thoughts, connecting with friends, or discovering new interests, Social Hub is the place to be. Join us today and experience the power of social networking!</p>
        </div>

        <div class="footer">
            <p>Need further help? <a href="mailto:support@socialhub.com">Contact Us</a> anytime!</p>
        </div>

        <div class="text-center">
            <a href="index.php" class="button-contact">Go Back to Home</a>
        </div>
    </div>

    <script>
        // Optional: Add animation or effects here
    </script>

</body>
</html>
