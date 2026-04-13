<?php
session_start();

include("includes/connection.php");


if (isset($_POST['login'])) {

    $email = htmlentities(mysqli_real_escape_string($conn, $_POST['email']));
    $pass = htmlentities(mysqli_real_escape_string($conn, $_POST['pass']));

    // Check if the login is for the admin
    $admin_email = 'admin@example.com'; // Default admin email
    $admin_pass = 'admin123';           // Default admin password

    if ($email == $admin_email && $pass == $admin_pass) {
        $_SESSION['admin'] = true;  // Set a session for the admin
        echo "<script>window.open('admin/admin.php', '_self')</script>";  // Redirect to admin dashboard
    } else {
        // Check for normal user credentials
        $select_user = "SELECT * FROM users WHERE user_email='$email' AND user_pass='$pass' AND status='verified'";
        $query = mysqli_query($conn, $select_user);
        $check_user = mysqli_num_rows($query);

        if ($check_user == 1) {
            $_SESSION['user_email'] = $email;
            echo "<script>window.open('home.php', '_self')</script>";
        } else {
            echo "<script>alert('Your Email or Password is incorrect')</script>";
        }
    }
}
// Check if the page was accessed directly
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'localhost/social_network') === false) {
    // If the referrer is not from the allowed domain, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
