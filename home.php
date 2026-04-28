<?php
session_start(); // Start the session at the beginning of the page
include_once("includes/header.php");

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("location: index.php"); // Redirect to login page if not logged in
    exit(); // Stop further execution of the page
}

// Fetch the logged-in user's email and use it to get their profile info
$user_email = $_SESSION['user_email'];

// Use prepared statements to prevent SQL injection
$get_user = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
$get_user->bind_param("s", $user_email); // "s" for string
$get_user->execute();
$run_user = $get_user->get_result();
$row = $run_user->fetch_array();

$user_name = $row['user_name'];
$user_image = $row['user_image'];
$user_id = $row['user_id']; // Assuming user_id is available
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($user_name); ?>'s Profile</title>
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
            background: url('images/background3.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: backgroundMove 50s linear infinite;
            
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

        /* Profile Image */
        .profile-img {
            border-radius: 50%;
            width: 140px;
            height: 140px;
            object-fit: cover;
            margin-top: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            animation: fadeInUp 1s ease-in-out;
        }

        .profile-img:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        }

        /* Profile Header */
        .profile-header {
            text-align: center;
            margin: 20px 0;
            color: #006aff;
            animation: fadeInUp 1s ease-in-out;
        }

        .profile-header h1 {
            font-size: 30px;
            margin-top: 10px;
            transition: color 0.3s, text-decoration 0.3s;
            animation: fadeInUp 1s ease-in-out;
        }

        .profile-header h1:hover {
            color: #0056b3;
            text-decoration: underline;
            cursor: pointer;
        }

        /* Post Form */
        .post-form {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 1s ease-in-out;
        }

        .post-form textarea {
            resize: none;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 20px;
        }

        .post-form .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .post-form .btn {
            width: 120px;
            border-radius: 20px;
        }

        .image-preview {
            margin-top: 15px;
            max-width: 100%;
            max-height: 300px;
            display: none;
        }

        /* News Feed */
        .news-feed-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #4a4a4a;
            margin-top: 20px;
            margin-bottom: 15px;
            animation: fadeInUp 1s ease-in-out;
        }

        .post {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: flex-start;
            animation: fadeInUp 1s ease-in-out;
        }

        .post img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
            animation: fadeInUp 1s ease-in-out;
        }

        .post-content {
            flex: 1;
            animation: fadeInUp 1s ease-in-out;
        }
        p{
            animation: fadeInUp 1s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Profile Header -->
        <div class="row profile-header">
            <div class="col-sm-12">
                <a href="profile.php?id=<?php echo $user_id; ?>">
                    <img src="users/<?php echo htmlspecialchars($user_image); ?>" alt="Profile Image" class="profile-img">
                </a>
                <h1>
                    <a href="profile.php?id=<?php echo $user_id; ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($user_name); ?>
                    </a>
                </h1>
                <p>Email: <?php echo htmlspecialchars($user_email); ?></p> <!-- Display the logged-in user's email -->
            </div>
        </div>

        <!-- Post Form -->
        <div class="row p">
            <div class="col-sm-12">
                <div class="post-form">
                    <form action="home.php?id=<?php echo $user_id; ?>" method="post" id="f" enctype="multipart/form-data">
                        <div>
                            <textarea class="form-control" id="content" rows="3" name="content" placeholder="What's on your mind?"></textarea>
                        </div>
                        <div class="form-group button-group">
                            <label class="btn btn-warning">
                                Select Image
                                <input type="file" name="upload_image" style="display: none;" id="imageUpload" onchange="previewImage(event)">
                            </label>
                            <button type="submit" id="btn-post" class="btn btn-success" name="sub">Post</button>
                        </div>
                        <!-- Image Preview -->
                        <img id="imagePreview" class="image-preview">
                    </form>
                    <?php insertPost(); ?>
                </div>
            </div>
        </div>

        <!-- News Feed -->
        <div class="row p">
            <div class="col-sm-12">
                <div class="news-feed-title">News Feed</div>
                <?php echo get_posts(); ?>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = URL.createObjectURL(event.target.files[0]);
            imagePreview.style.display = 'block';
        }
    </script>
</body>
</html>
