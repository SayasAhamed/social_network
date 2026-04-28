<?php
// Start session at the very beginning of the script
session_start();

// Start output buffering to avoid "headers already sent" issues
ob_start();

include("includes/connection.php");

if (isset($_POST['login'])) {

    $email = htmlentities(mysqli_real_escape_string($conn, $_POST['email']));
    $pass = htmlentities(mysqli_real_escape_string($conn, $_POST['pass']));

    // Check if the login is for the admin
    $admin_email = 'admin@example.com'; // Default admin email
    $admin_pass = 'admin123';           // Default admin password

    if ($email == $admin_email && $pass == $admin_pass) {
        $_SESSION['admin'] = true;  // Set a session for the admin
        header("Location: admin/admin.php");  // Redirect to admin dashboard
        exit();  // Ensure to exit after redirect
    } else {
        // Check for normal user credentials
        $select_user = "SELECT * FROM users WHERE user_email='$email' AND user_pass='$pass' AND status='verified'";
        $query = mysqli_query($conn, $select_user);
        $check_user = mysqli_num_rows($query);

        if ($check_user == 1) {
            $_SESSION['user_email'] = $email;  // Set session for the logged-in user
            header("Location: home.php");  // Redirect to home page
            exit();  // Ensure to exit after redirect
        } else {
            echo "<script>alert('Your Email or Password is incorrect')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Social Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>

        /* Fade-in and Slide-in animations */
        @keyframes fadeInUp {
                0% { opacity: 0; transform: translateY(30px); }
                100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
                0% { opacity: 0; transform: translateX(-100px); }
                100% { opacity: 1; transform: translateX(0); }
        }
        

        body {
            background-image: url('images/background.png');
            background-size: cover;
            margin-bottom:10px;
            background-position: center;
            background-repeat: no-repeat;
            overflow-x: hidden;
            animation: panBackground 50s linear infinite;
        }
        .container {
            margin-top: 100px;
        }
        .main-content {
            background-color: rgba(255, 255, 255, 0.3); /* Transparent white */
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); /* Softer shadow for glass effect */
            backdrop-filter: blur(10px); /* Blur effect for glassmorphism */
            -webkit-backdrop-filter: blur(10px); /* Safari support */
            padding: 40px 50px;
            max-width: 400px;
            margin: auto;
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border for glass outline */
        }
        .header h3 {
            margin-bottom: 30px;
            color: #333;
        }
        .well {
            background-color: #187FAB;
            border-radius: 8px 8px 0 0;
            padding: 15px;
            text-align: center;
            color: white;
        }
        #signin {
            width: 100%;
            border-radius: 30px;
            background-color: #187FAB;
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        #signin:hover {
            background-color: #00de43;
            transform: scale(1.05);
        }
        .overlap-text {
            position: relative;
            margin-bottom: 20px;
        }
        .overlap-text input[type="password"],
        .overlap-text input[type="text"] {
            padding-right: 40px;
        }
        .overlap-text i {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: #999;
        }
        .overlap-text a {
            position: absolute;
            top: 8px;
            right: 40px;
            font-size: 14px;
            color: #187FAB;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .overlap-text a:hover {
            color: #0F5B7F;
        }
        .signup-link {
            color: #95e2f5;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }
        .signup-link:hover {
            color: #03fccf;
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
        .floating-contact {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            align-items: center;
            background-color: #187FAB;
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .floating-contact a {
            color: white;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .floating-contact i {
            margin-right: 8px;
            font-size: 20px;
        }

        .floating-contact:hover {
            background-color: #00de43;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
        }

        @keyframes panBackground {
            0% {
                background-position: center;
            }
            50% {
                background-position: center top;
            }
            100% {
                background-position: center;
            }
        }

        .p7{
            animation: fadeInUp 3s ease-in-out;
        }
        .p6{
            animation: fadeInUp 1s ease-in-out;
        }
        .floating-contact {
            background-color: #0fff8b;
            color:#ffffff;
        }
    </style>
</head>
<body>
    <!-- Floating Customer Care Icon -->
    <div class="floating-contact p7">
        <a href="contact_us.php" class="contact-icon" title="Contact Us">
            <i class="glyphicon glyphicon-earphone"></i> Contact Us
        </a>
    </div>

    <div class="container p6">
        <div class="main-content">
            <div class="well">
                <h1>Social Hub</h1>
            </div>
            <div class="header">
                <h3><strong>Login to Social Hub</strong></h3>
            </div>
            <form action="" method="post">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required="required" class="form-control input-md">
                </div>
                <div class="form-group overlap-text">
                    <input type="password" name="pass" placeholder="Password" required="required" class="form-control input-md" id="passwordField">
                    <i class="glyphicon glyphicon-eye-close" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                    <a data-toggle="tooltip" title="Reset Password" href="forgot_password.php">Forgot?</a>
                </div>
                <div class="footer">
                    <a class="signup-link" data-toggle="tooltip" title="Create Account!" href="signup.php">Don't have an account?</a>
                </div>
                <button id="signin" class="btn btn-lg" name="login">Login</button>
                <div>
                    <a href="main.php" class="btn-lg" style="color:white; font-size:10px; float:right;" name="login">Go back</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('passwordField');
            var toggleIcon = document.getElementById('togglePassword');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('glyphicon-eye-close');
                toggleIcon.classList.add('glyphicon-eye-open');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('glyphicon-eye-open');
                toggleIcon.classList.add('glyphicon-eye-close');
            }
        }
    </script>
</body>
</html>
