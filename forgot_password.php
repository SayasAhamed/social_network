<?php
session_start(); // Start the session at the top

include("includes/connection.php");

if (isset($_POST['submit'])) {
    $email = htmlentities(mysqli_real_escape_string($conn, $_POST['email']));
    $best_friend = htmlentities(mysqli_real_escape_string($conn, $_POST['best_friend']));
    $home_town = htmlentities(mysqli_real_escape_string($conn, $_POST['home_town']));
    $pet_name = htmlentities(mysqli_real_escape_string($conn, $_POST['pet_name']));
    $favorite_color = htmlentities(mysqli_real_escape_string($conn, $_POST['favorite_color']));
    $favorite_food = htmlentities(mysqli_real_escape_string($conn, $_POST['favorite_food']));
    $favorite_movie = htmlentities(mysqli_real_escape_string($conn, $_POST['favorite_movie']));

    // Fetch user data from the database
    $select_user = "SELECT * FROM users WHERE user_email='$email'";
    $query = mysqli_query($conn, $select_user);
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        // Decode the stored JSON answers
        $recovery_answers = json_decode($user['recovery_account'], true);

        // Check each answer
        $is_valid = true;
        $message = "";

        if ($recovery_answers['best_friend'] !== $best_friend) {
            $is_valid = false;
            $message .= "Best friend's name is incorrect.<br>";
        }

        if ($recovery_answers['home_town'] !== $home_town) {
            $is_valid = false;
            $message .= "Home town is incorrect.<br>";
        }

        if ($recovery_answers['pet_name'] !== $pet_name) {
            $is_valid = false;
            $message .= "Pet name is incorrect.<br>";
        }

        if ($recovery_answers['favorite_color'] !== $favorite_color) {
            $is_valid = false;
            $message .= "Favorite color is incorrect.<br>";
        }

        if ($recovery_answers['favorite_food'] !== $favorite_food) {
            $is_valid = false;
            $message .= "Favorite food is incorrect.<br>";
        }

        if ($recovery_answers['favorite_movie'] !== $favorite_movie) {
            $is_valid = false;
            $message .= "Favorite movie is incorrect.<br>";
        }

        // If all answers are correct, proceed to password change
        if ($is_valid) {
            $_SESSION['user_email'] = $email;
            $_SESSION['password_recovery_success'] = true; // Set the success flag
            echo "<script>window.open('change_password.php', '_self');</script>";
            exit();
        } else {
            // Display the error message for incorrect answers
            echo "<script>alert('Your email or one of the answers is incorrect: <strong>" . $message . "</strong>');</script>";
        }
    } else {
        echo "<script>alert('Email not found in the database');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgotten Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>
        
        /* Background Animation */
        @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

        body {
            background-image: url('images/background.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            overflow-x: hidden;
            animation: panBackground 40s linear infinite; /* Animation only on background */
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.0), rgba(0, 0, 0, 0.0));
            z-index: -2;
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
                background-position: center top; /* Adjust movement to desired direction */
            }
            100% {
                background-position: center;
            }
        }
        .p7{
            animation: fadeInUp 1s ease-in-out;
        }

        .p6{
            animation: fadeInUp 1s ease-in-out;
        }
        
    </style>
</head>
<body>
    <div class="row p7">
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
                    <h3 style="text-align:center;"><strong>Forgot Password</strong></h3><hr>
                </div>
                <div class="l_pass">
                <form action="" method="post">
                    
                    <!--Enter your email below-->
                    <pre style="background-color: #adfff0;" class="text">Enter Your Email Below</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" id="email" class="form-control" name="email" placeholder="Enter Your Email Here.." required>
                    </div><br><br><br>

                    <!-- Security questions -->
                    <pre class="text">Enter Your Best Friend's Name</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="best_friend" placeholder="Best Friend's Name" required>
                    </div><br>

                    <pre class="text">Enter your Home Town</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="home_town" placeholder="Home Town" required>
                    </div><br>

                    <pre class="text">Enter your Pet Name</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="pet_name" placeholder="Pet Name" required>
                    </div><br>

                    <pre class="text">Enter your Favorite Color</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="favorite_color" placeholder="Favorite Color" required>
                    </div><br>

                    <pre class="text">Enter your Favorite Food</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="favorite_food" placeholder="Favorite Food" required>
                    </div><br>

                    <pre class="text">Enter your Favorite Movie</pre>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input type="text" class="form-control" name="favorite_movie" placeholder="Favorite Movie" required>
                    </div><br>

                    <center><button id="signup" type="submit" name="submit" class="btn btn-info">Verify Answers</button></center>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>