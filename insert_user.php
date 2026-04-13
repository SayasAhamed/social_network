<?php
include("includes/connection.php");

if(isset($_POST['sign_up'])){

    $first_name = htmlentities(mysqli_real_escape_string($conn,$_POST['first_name']));
    $last_name = htmlentities(mysqli_real_escape_string($conn,$_POST['last_name']));
    $pass = htmlentities(mysqli_real_escape_string($conn,$_POST['u_pass']));
    $email = htmlentities(mysqli_real_escape_string($conn,$_POST['user_email']));
    $country = htmlentities(mysqli_real_escape_string($conn,$_POST['u_country']));
    $gender = htmlentities(mysqli_real_escape_string($conn,$_POST['u_gender']));
    $birthday = htmlentities(mysqli_real_escape_string($conn,$_POST['u_birthday']));
    $status = "verified";
    $posts = "no";
    $newgid = sprintf('%05d', rand(0, 999999));

    // Creating a username
    $username = strtolower($first_name . "_" . $last_name . "_" . $newgid);

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE user_email='$email'";
    $run_email = mysqli_query($conn, $check_email_query);
    $check = mysqli_num_rows($run_email);

    if(strlen($pass) < 9){
        echo "<script>alert('Password should be minimum 9 characters!')</script>";
        exit();
    }

    if($check == 1){
        echo "<script>alert('Email already exists, Please try using another email')</script>";
        echo "<script>window.open('signup.php', '_self')</script>";
        exit();
    }

    // Choose a default profile picture
    $rand = rand(1, 3);
    if($rand == 1) $profile_pic = "head_red.png";
    else if($rand == 2) $profile_pic = "head_sun_flower.png";
    else if($rand == 3) $profile_pic = "head_turqoise.png";

    // Capture security question answers and store them in JSON format
    $security_answer_1 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_1']));
    $security_answer_2 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_2']));
    $security_answer_3 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_3']));
    $security_answer_4 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_4']));
    $security_answer_5 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_5']));
    $security_answer_6 = htmlentities(mysqli_real_escape_string($conn,$_POST['security_question_6']));

    $recovery_account = json_encode([
    'best_friend' => $security_answer_1,
    'home_town' => $security_answer_2,
    'pet_name' => $security_answer_3,
    'favorite_color' => $security_answer_4,
    'favorite_food' => $security_answer_5,
    'favorite_movie' => $security_answer_6
    ]);

    // Insert user data into the database
    $insert = "INSERT INTO users (f_name, l_name, user_name, describe_user, Relationship, user_pass, user_email, user_country, user_gender, user_birthday, user_image, user_cover, user_reg_date, status, posts, recovery_account)
    VALUES('$first_name', '$last_name', '$username', 'Hello Social Hub. This is the default status!', '...', '$pass', '$email', '$country', '$gender', '$birthday', '$profile_pic', 'default_cover.jpg', NOW(), '$status', '$posts', '$recovery_account')";

    $query = mysqli_query($conn, $insert);

    if($query){
        echo "<script>alert('Well Done $first_name, you are good to go.')</script>";
        echo "<script>window.open('signin.php', '_self')</script>";
    }
    else{
        echo "<script>alert('Registration failed, please try again!')</script>";
        echo "<script>window.open('signup.php', '_self')</script>";
    }
}
?>
