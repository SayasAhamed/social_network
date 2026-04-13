<?php
session_start();
include("includes/connection.php");

if (isset($_POST['sign_up'])) {

    // Sanitize user inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_pass = mysqli_real_escape_string($conn, $_POST['u_pass']);
    $user_country = mysqli_real_escape_string($conn, $_POST['u_country']);
    $user_gender = mysqli_real_escape_string($conn, $_POST['u_gender']);
    $user_birthday = mysqli_real_escape_string($conn, $_POST['u_birthday']);
    $user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);

    // Security Question and Answer (six questions)
    $security_answer_1 = mysqli_real_escape_string($conn, $_POST['security_question_1']);
    $security_answer_2 = mysqli_real_escape_string($conn, $_POST['security_question_2']);
    $security_answer_3 = mysqli_real_escape_string($conn, $_POST['security_question_3']);
    $security_answer_4 = mysqli_real_escape_string($conn, $_POST['security_question_4']);
    $security_answer_5 = mysqli_real_escape_string($conn, $_POST['security_question_5']);
    $security_answer_6 = mysqli_real_escape_string($conn, $_POST['security_question_6']);

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE user_email='$user_email'";
    $check_query = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($check_query) > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Prepare the data to be saved in JSON format
        $recovery_account = json_encode([
            'best_friend' => $security_answer_1,
            'home_town' => $security_answer_2,
            'pet_name' => $security_answer_3,
            'favorite_color' => $security_answer_4,
            'favorite_food' => $security_answer_5,
            'favorite_movie' => $security_answer_6
        ]);

        // Hash password for security
        $hashed_password = password_hash($user_pass, PASSWORD_BCRYPT);

        // Additional columns
        $status = 'active'; // Default status
        $posts = 0; // Default number of posts
        $report = ''; // Default report value
        $report_status = 'not reported'; // Default report status
        $submitted_time = date('Y-m-d H:i:s'); // Current timestamp

        // Insert the user data into the database (including the recovery_account JSON)
        $sql = "INSERT INTO users (first_name, last_name, user_email, user_pass, user_country, user_gender, 
            user_birthday, user_phone, recovery_account, status, posts, report, report_status, submitted_time) 
            VALUES ('$first_name', '$last_name', '$user_email', '$hashed_password', '$user_country', '$user_gender', 
            '$user_birthday', '$user_phone', '$recovery_account', '$status', '$posts', '$report', '$report_status', '$submitted_time')";

        if (mysqli_query($conn, $sql)) {
            // Registration successful
            echo "Registration successful!";
        } else {
            // Error occurred
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            background-attachment: fixed;
            background-position: center;
            overflow-x: hidden;
            animation: panBackground 40s linear infinite;
        }
        
        .main-content {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .header {
            margin-bottom: 20px;
        }
        
        .well {
            background-color: #187FAB;
            color: white;
        }
        
        #signup {
            width: 100%;
            border-radius: 30px;
            background-color: #187FAB;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        #signup:hover {
            background-color: #00de43;
            color: white;
            transform: scale(1.05);
        }
        
        .account-link {
            color: #95e2f5;
            transition: color 0.3s ease, text-decoration 0.3s ease;
            font-weight: bold;
        }
        
        .account-link:hover {
            color: #03fccf;
            text-decoration: underline;
        }

        .overlap-text {
            position: relative;
            margin-bottom: 20px;
        }
        
        .overlap-text .input-group-addon {
            cursor: pointer;
        }

        .input-group-addon {
            background-color: #e6e6e6;
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
        
        .p6 {
            animation: fadeInUp 1s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="row p6">
        <div class="col-sm-12">
            <div class="well">
                <center><h1>Social Hub</h1></center>
            </div>
        </div>
    </div>
    <div class="row p6">
        <div class="col-sm-12">
            <div class="main-content">
                <div class="header">
                    <h3 class="text-center" style="color: #ffff;"><strong>Join Social Hub</strong></h3>
                    <hr>
                </div>
                <form action="insert_user.php" method="post">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="email" type="email" class="form-control" placeholder="Email" name="user_email" required>
                    </div><br>
                    <div class="input-group overlap-text">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="passwordField" type="password" class="form-control" placeholder="Password" name="u_pass" required>
                        <span class="input-group-addon" onclick="togglePasswordVisibility()">
                            <i id="togglePassword" class="glyphicon glyphicon-eye-close"></i>
                        </span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-chevron-down"></i></span>
                        <select class="form-control" name="u_country" required>
                            <option disabled selected>Select your Country</option>
                            <option>Sri Lanka</option>
                            <option>India</option>
                            <option>USA</option>
                            <option>UK</option>
                            <option>Japan</option>
                            <option>Saudi Arabia</option>
                            <option>France</option>
                            <option>Germany</option>
                            <option>Italy</option>
                            <option>China</option>
                        </select>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-chevron-down"></i></span>
                        <select class="form-control" name="u_gender" required>
                            <option disabled selected>Select your Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Others</option>
                        </select>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="date" class="form-control" name="u_birthday" required>
                    </div><br>
                    <!-- Phone Number Input -->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                        <input type="text" class="form-control" placeholder="Phone Number" name="user_phone" required>
                    </div><br><br><br>

                    <!-- Security Questions -->
                    <div>
                        <h3 style="text-align:center; color: #ffff;"><strong>Security Questions</strong></h3>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_1" placeholder="Enter Your Best Friend's Name" required>
                    </div><br>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_2" placeholder="Enter your Home Town" required>
                    </div><br>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_3" placeholder="Enter your Pet Name" required>
                    </div><br>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_4" placeholder="Enter your Favorite Color" required>
                    </div><br>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_5" placeholder="Enter your Favorite Food" required>
                    </div><br>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                        <input type="text" class="form-control" name="security_question_6" placeholder="Enter your Favorite Movie" required>
                    </div><br>

                    <!-- Submit Button -->
                    <button id="signup" class="btn btn-success" type="submit" name="sign_up">Sign Up</button><br><br>
                    <center><h5 style="color: #36fcff;">Already have an account? <a href="signin.php" class="account-link" style="gap:10px; padding-left:10px;">Sign In</a></h5></center>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('passwordField');
            const togglePasswordIcon = document.getElementById('togglePassword');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePasswordIcon.classList.remove('glyphicon-eye-close');
                togglePasswordIcon.classList.add('glyphicon-eye-open');
            } else {
                passwordField.type = 'password';
                togglePasswordIcon.classList.remove('glyphicon-eye-open');
                togglePasswordIcon.classList.add('glyphicon-eye-close');
            }
        }
    </script>
</body>
</html>