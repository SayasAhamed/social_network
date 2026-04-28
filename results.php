<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");
include ("functions/functions.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
}
?>
<html>
<head>
    <title>See Results</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($user_name); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    
</head>
<<style>
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
        

        /* Center Content */
        .container {
            padding-top: 50px;
            animation: fadeInUp 1s ease-in-out;
        }

        .row {
            margin-top: 20px;
        }

        .col-sm-12 {
            text-align: center;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #333;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Post Styling */
        .post-container {
            margin-top: 30px;
        }

        .post {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            animation: fadeInUp 1s ease-in-out;
        }

        .post:hover {
            transform: translateY(-5px);
        }

        /* Button Hover Effect */
        .btn {
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7e5f;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        

</style>>
<body>
<div class="container">
        <div class="row">
            <div class="col-sm-12">
                <center><h2>See Your Results Here!</h2></center>
                <div class="post-container">
                    <?php results();?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>