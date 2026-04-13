<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
}
?>

<html>
<head>
    <title>View Your Post</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    
    <style>
        /* Background Animation */
        @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

        body {
            background: url('images/Background2.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: backgroundMove 30s linear infinite;
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
        

        .comment-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .comment-box img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .comment-box h4 {
            margin: 0;
            font-size: 16px;
        }

        .comment-box small {
            color: #777;
        }

        .comment-box p {
            margin: 10px 0 0;
        }

        .comment-form {
            margin-top: 20px;
        }

        .comment-form textarea {
            resize: none;
            width: 52%;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .comment-form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .comment-form button:hover {
            background-color: #0056b3;
        }

        .p6{
            animation: fadeInUp 1s ease-in-out;
        }

    </style>
</head>
<body>
    <div class="container p6">
        <div class="row">
            <div class="col-sm-12 p6">
                <center><h2>Comments</h2></center><br>
                <div class="row">
                    <?php single_post(); ?>
                </div>
                <div class="comment-form">
                    <form method="post">
                        <textarea style="position: relative; left: 274px;" name="comment" placeholder="Write your comment here!"></textarea>
                        <button type="submit" name="reply" style="position: relative;top:-60px; Left: 280px;">Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
