<!DOCTYPE html>
<html>
<head>
    <title>Social Hub Login and Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>

    /* Background Animation */
    @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

    body {
        background-image: url('images/image.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow-x: hidden;
        animation: backgroundMove 60s linear infinite;
        font-family: Arial, sans-serif;
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

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        animation: fadeInUp 1s ease-in-out;
    }
    #centered1, #centered2, #centered3 {
        position: absolute;
        font-size: 6vw;
        transform: translate(-50%, -50%);
        color: white;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }
    #centered1 {
        top: 25%;
        left: 30%;
    }
    #centered2 {
        top: 50%;
        left: 40%;
    }
    #centered3 {
        top: 70%;
        left: 30%;
    }
    @media (max-width: 768px) {
        #centered1, #centered2, #centered3 {
            font-size: 8vw;
            position: relative;
            top: auto;
        }
    }
    #signup, #login {
        width: 60%;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    #signup {
        background-color: #1da1f2;
        color: white;
        border: none;
    }
    #signup:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
    #login {
        background-color: #fff;
        border: 1px solid #1da1f2;
        color: #1da1f2;
    }
    #login:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        border: 2px solid #1da1f2;
    }

    .well {
            background-color: rgba(255, 255, 255, 0.3); /* Light transparent background for glass effect */
            color: white;
            font-family: 'Roboto', sans-serif;
            font-size: 2rem;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            backdrop-filter: blur(10px); /* Glass blur effect */
            -webkit-backdrop-filter: blur(10px); /* Safari support */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3); /* Light border for glass outline */
            position: relative; /* Required for the glow effect */
        }

        /* Glowing border animation */
        @keyframes glowing {
            0% {
                box-shadow: 0 0 5px #1da1f2, 0 0 10px #1da1f2, 0 0 15px #1da1f2, 0 0 20px #1da1f2;
            }
            50% {
                box-shadow: 0 0 10px #1da1f2, 0 0 20px #1da1f2, 0 0 30px #1da1f2, 0 0 40px #1da1f2;
            }
            100% {
                box-shadow: 0 0 5px #1da1f2, 0 0 10px #1da1f2, 0 0 15px #1da1f2, 0 0 20px #1da1f2;
            }
        }

        .well:hover {
            animation: glowing 1.5s infinite; /* Animate glowing on hover */
        }

    /* Logo attraction effect */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    .logo-img {
        animation: pulse 3s infinite;
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        animation: bounce 0.6s;
        transform: scale(1.1);
    }
    .heading1{
        color:black;
    }
    .heading1:hover{
        color:white;
        font-family: 'Roboto', sans-serif;
        cursor:pointer;
    }
    .heading1:hover{
        color:#b800f5;
        transition: color 0.5s;
        transition-timing-function: ease-in-out;
        animation: bounce 0.6s;
        transform: scale(1.2);
    }

</style>
<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <center><a href="contact_us.php" style="text-decoration:none; color: #ffff;"><h1 class='heading1'><strong>Social Hub</strong></h1></a></center>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" style="left:0.5%;">
            <img src="images/SocialHub sidepage.png" class="img-rounded well" title="Coding cafe" width="650px" height="565px">
            <div id="centered1" class="centered">
                <h3><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;<strong>Follow Your Interests.</strong></h3>
            </div>
            <div id="centered2" class="centered">
                <h3><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;<strong>Hear what People are talking about.</strong></h3>
            </div>
            <div id="centered3" class="centered">
                <h3><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;<strong>Join the Conversation.</strong></h3>
            </div>
        </div>
        <div class="col-sm-6" style="left:8%;">
            <img src="images/SocialHub Logo.png" class="img-rounded logo-img" title="Social Hub Logo" width="270px" height="200px">

            <h2 style='color:#b8b8b8;'><strong>See what's happening in <br> the world right now</strong></h2><br><br>
            <h4 style='color:#aaa9ab;'><strong>Join Social Hub Today.</strong></h4>
            <form method="post" action="">
                <button id="signup" class="btn btn-info btn-lg" name="signup">Sign up</button><br><br>
                <?php
                    if (isset($_POST['signup'])) {
                        echo "<script>window.open('signup.php','_self')</script>";
                    }
                ?>
                <button id="login" class="btn btn-info btn-lg" name="login">Login</button><br><br>
                <?php
                    if (isset($_POST['login'])) {
                        echo "<script>window.open('signin.php','_self')</script>";
                    }
                ?>
            </form>
        </div>
    </div>
</body>
</html>
