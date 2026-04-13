<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    include_once("includes/header.php"); // Use include_once here

    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
    }

    $user = $_SESSION['user_email'];
    $get_user = "SELECT * FROM users WHERE user_email='$user'";
    $run_user = mysqli_query($conn, $get_user);
    $row = mysqli_fetch_array($run_user);

    $user_name = $row['user_name'];
    $user_image = $row['user_image'];
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($user_name); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    <style>
        .profile-img {
            border-radius: 100%;
            width: 140px;
            height: 140px;
            object-fit: cover;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .post-header img {
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .post-header .user-name {
            font-weight: bold;
            margin-right: 10px;
        }
        .post-header .post-date {
            color: #777;
        }
        .post {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .post-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
            object-fit: cover;
        }
        .post-content img.full-size {
            width: auto;
            height: auto;
            display: block;
            margin: 10px auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <img src="users/<?php echo htmlspecialchars($user_image); ?>" alt="Profile Image" class="profile-img">
                <h1 style="font-size: 30px;color:#006aff;"><b><?php echo htmlspecialchars($user_name); ?></b></h1>
            </div>
        </div>
        <div class="row">
            <div id="insert_post" class="col-sm-12">
                <center>
                    <form action="home.php?id=<?php echo $user_id; ?>" method="post" id="f" enctype="multipart/form-data">
                        <div class="form-group">
                            <textarea class="form-control" style="font-size:15px;" id="content" rows="5" name="content" placeholder="What's on your mind?"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="btn btn-warning" style="margin-left:10px; margin-top:15px;" id="upload_image_button">
                                Select Image
                                <input type="file" name="upload_image" style="display: none;">
                            </label>
                        </div>
                        <button type="submit" id="btn-post" class="btn btn-success" name="sub">Post</button>
                    </form>
                    <?php insertPost(); ?>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <center>
                    <h2><strong>News Feed</strong></h2>
                    <br>
                </center>
                <?php echo get_posts(); ?>
            </div>
        </div>
    </div>
</body>
</html>
