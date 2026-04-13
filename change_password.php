<!DOCTYPE html>
<?php
    session_start();
    include_once("includes/connection.php");
    $user_email = $_SESSION['user_email'];
    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
    }
?>
<html>
<head>
    <title>Forgotten Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>
        /* Full-screen animated background */
        body {
            background-image: url('images/Background.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            overflow-x: hidden;
            animation: panBackground 40s linear infinite;
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

        /* Overlay gradient */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(0, 0, 0, 0.2));
            z-index: -1;
        }

        /* Glass effect on the form */
        .main-content {
            width: 50%;
            margin: 10px auto;
            padding: 40px 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        /* Header and button styling */
        .well {
            background-color: #187fab;  
        }
        #signup {
            width: 60%;
            border-radius: 30px;
        }

        /* Keyframes for background panning effect */
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
        .p6{
            animation: fadeInUp 1s ease-in-out;
        }
    </style>

    <script>
        // Function to trigger the success message popup
        function showSuccessPopup(message) {
            alert(message); // Display the alert message
        }

        // Function to toggle password visibility
        function togglePasswordVisibility() {
            const passwordField1 = document.getElementById('password1');
            const passwordField2 = document.getElementById('password2');
            const togglePasswordIcon = document.getElementById('togglePassword');
            
            // Toggle visibility for both password fields
            if (passwordField1.type === 'password') {
                passwordField1.type = 'text';
                passwordField2.type = 'text';
                togglePasswordIcon.classList.remove('glyphicon-eye-close');
                togglePasswordIcon.classList.add('glyphicon-eye-open');
            } else {
                passwordField1.type = 'password';
                passwordField2.type = 'password';
                togglePasswordIcon.classList.remove('glyphicon-eye-open');
                togglePasswordIcon.classList.add('glyphicon-eye-close');
            }
        }
    </script>
</head>

<body>
    <div class="row p6">
        <div class="col-md-12">
            <div class="well">
                <center><h1 style="color:white;"><strong>Social Hub</strong></h1></center>
            </div>
        </div> 
    </div>
    <div class="row p6">
        <div class="col-md-12">
            <div class="main-content">
                <div class="header">
                    <h3 style="text-align:center;"><strong>Change Your Password.</strong></h3><hr>
                </div>
                <div class="l_pass">
                    <form action="" method="post">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" id="password1" class="form-control" name="pass" placeholder="New Password" required>
                            <span class="input-group-addon" onclick="togglePasswordVisibility()">
                            <i id="togglePassword" class="glyphicon glyphicon-eye-close"></i>
                        </div><br>
                    </div>


                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" id="password2" class="form-control" name="pass1" placeholder="Re-enter New Password" required>
                            <span class="input-group-addon" onclick="togglePasswordVisibility()">
                            <i id="togglePassword" class="glyphicon glyphicon-eye-close"></i>
                        </div><br>
                        <center><button id="signup" class="btn btn-info btn-lg" name="change">Change Password</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    if (isset($_POST['change'])) {
        $user = $_SESSION['user_email'];
        $get_user = "SELECT * FROM users WHERE user_email='$user'";
        $run_user = mysqli_query($conn, $get_user);
        $row = mysqli_fetch_array($run_user);

        $user_id = $row['user_id'];
        $pass = htmlentities(mysqli_real_escape_string($conn, $_POST['pass']));
        $pass1 = htmlentities(mysqli_real_escape_string($conn, $_POST['pass1']));

        if ($pass == $pass1) {
            if (strlen($pass) >= 6 && strlen($pass) <= 60) {
                $update = "UPDATE users SET user_pass='$pass' WHERE user_id='$user_id'";
                $run = mysqli_query($conn, $update);
                echo '<script>showSuccessPopup("Password Changed Successfully");</script>';
                echo '<script>window.open("home.php", "_self")</script>';
            } else {
                echo '<script>alert("Password must be between 6 to 60 characters")</script>';
            }
        } else {
            echo '<script>alert("Passwords do not match")</script>';
            echo '<script>window.open("change_password.php", "_self")</script>';
        }
    }
?>
