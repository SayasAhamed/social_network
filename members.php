<!DOCTYPE html>
<?php
    session_start();
    include("includes/connection.php");
    include("includes/header.php");

    // Check if the user is logged in and has a valid user_id in the session
    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");  // Redirect to login page if not logged in
        exit();
    }
?>

<html>
    <head>
        <title>Find People</title>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
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



            .search-container {
                position: relative;
                z-index: 1;
                margin-top: 50px;
                text-align: center;
                animation: fadeInUp 1s ease-in-out;
            }

            .search-container h2 {
                font-weight: 700;
                font-size: 36px;
                color: #fff;
                margin-bottom: 20px;
                animation: slideInLeft 1s ease-in-out;
            }

            /* Search form container */
            .search-form {
                display: flex;
                justify-content: center;
                gap: 10px;
                animation: fadeInUp 1s ease-in-out;
            }

            .search-form input[type="text"] {
                width: 60%;
                padding: 12px;
                border-radius: 30px;
                border: 1px solid #ddd;
                font-size: 16px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            /* Focus effect */
            .search-form input[type="text"]:focus {
                outline: none;
                border-color: #3897f0;
                box-shadow: 0px 4px 12px rgba(56, 151, 240, 0.2);
            }

            .search-form button {
                padding: 12px 20px;
                border-radius: 30px;
                background-color: #3897f0;
                color: white;
                border: none;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .search-form button:hover {
                background-color: #3079db;
                transform: scale(1.05);
            }

            /* Animation effect for the search results */
            .search-results {
                margin-top: 30px;
                animation: fadeInUp 1s ease-in-out;
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
        </style>
    </head>

<body>
    <div class="container search-container">
        <h2 style="color:#4d58ff;">Find New People</h2>
        <form action="" method="get" class="search-form">
            <input type="text" placeholder="Search for friends..." name="search_user">
            <button type="submit" name="search_user_btn">Search</button>
        </form>
    </div>

    <div class="container search-results">
        <?php search_user(); ?>
    </div>
</body>
</html>
