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
    <title>Find People</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .profile-info {
            background-color: #e6e6e6;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-img {
            border-radius: 50%;
            border: 3px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 150px;
            height: 150px;
        }
        .info-list {
            list-style-type: none;
            padding: 0;
            font-size: 14px;
        }
        .info-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        #own_posts {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .post-header img {
            margin-right: 10px;
        }
        .post-img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-group .btn {
            margin-top: 10px;
        }
        .btn-danger {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php 
        if (isset($_GET['u_id'])){
            $u_id = $_GET['u_id'];
            if($u_id < 0 || $u_id == ""){
                echo "<script>window.open('home.php', '_self')</script>";
            } else {
        ?>
        <div class="row">
            <div class="col-sm-3">
                <?php
                if(isset($_GET['u_id'])){
                    global $conn;

                    $user_id = $_GET['u_id'];

                    $select = "SELECT * FROM users WHERE user_id = $user_id";
                    $run = mysqli_query($conn, $select);
                    $row = mysqli_fetch_array($run);

                    $id = $row['user_id'];
                    $image = $row['user_image'];
                    $name = $row['user_name'];
                    $f_name = $row['f_name'];
                    $l_name = $row['l_name'];
                    $describe_user = $row['describe_user'];
                    $country = $row['user_country'];
                    $gender = $row['user_gender'];
                    $register_date = $row['user_reg_date'];

                    echo "
                        <div class='profile-info'>
                            <h2>Information about</h2>
                            <a href='user_profile.php?u_id=$user_id'>
                                <img src='users/$image' class='img-circle profile-img'>
                            </a>
                            <br><br>
                            <ul class='info-list'>
                                <li><strong>Username:</strong> $f_name $l_name</li>
                                <li><strong>Status:</strong> <span style='color: gray;'>$describe_user</span></li>
                                <li><strong>Gender:</strong> <span style='color: gray;'>$gender</span></li>
                                <li><strong>Country:</strong> <span style='color: gray;'>$country</span></li>
                                <li><strong>Registered:</strong> <span style='color: gray;'>$register_date</span></li>
                            </ul>";

                    $user = $_SESSION['user_email'];
                    $get_user =  "SELECT * FROM users WHERE user_email = '$user'";
                    $run_user = mysqli_query($conn, $get_user);
                    $row = mysqli_fetch_array($run_user);

                    $userown_id = $row['user_id'];

                    if($user_id == $userown_id){
                        echo "<a href='edit_profile.php?u_id=$userown_id' class='btn btn-success'>Edit Profile</a><br><br>";
                    }

                    echo "</div>";
                }}
                ?>
            </div>
            <div class="col-sm-9">
                <center><h1><strong><?php echo "$f_name $l_name";?></strong>'s Posts</h1></center>
                <?php
                global $conn;

                if(isset($_GET['u_id'])){
                    $u_id = $_GET['u_id'];
                }

                $get_posts = "SELECT * FROM posts WHERE user_id='$u_id' ORDER BY 1 DESC LIMIT 5";
                $run_post = mysqli_query($conn, $get_posts);

                // Get the current user's ID
                $current_user_email = $_SESSION['user_email'];
                $get_current_user = "SELECT * FROM users WHERE user_email = '$current_user_email'";
                $run_current_user = mysqli_query($conn, $get_current_user);
                $current_user_row = mysqli_fetch_array($run_current_user);
                $current_user_id = $current_user_row['user_id'];

                while($row_posts = mysqli_fetch_array($run_post)){
                    $post_id = $row_posts['post_id'];
                    $user_id = $row_posts['user_id'];
                    $content = $row_posts['post_content'];
                    $upload_image = $row_posts['upload_image'];
                    $post_date = $row_posts['post_date'];

                    $user = "SELECT * FROM users WHERE user_id = '$user_id' AND posts='yes'";
                    $run_user = mysqli_query($conn, $user);
                    $row_user = mysqli_fetch_array($run_user);

                    $user_name = $row_user['user_name'];
                    $f_name = $row_user['f_name'];
                    $l_name = $row_user['l_name'];
                    $user_image = $row_user['user_image'];

                    echo "<div id='own_posts'>";
                    echo "<div class='post-header'>
                            <img src='users/$user_image' class='img-circle profile-img' width='50' height='50'>
                            <div>
                                <h3><a href='user_profile.php?u_id=$user_id' style='text-decoration:none; color:#3897f0;'>$user_name</a></h3>
                                <h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                          </div>";

                    echo "<div class='post-content'>";
                    if($content == "No" && strlen($upload_image) >= 1){
                        echo "<img src='imagepost/$upload_image' class='post-img'>";
                    } elseif (strlen($content) >= 1 && strlen($upload_image) >= 1) {
                        echo "<p>$content</p>
                              <img src='imagepost/$upload_image' class='post-img'>";
                    } else {
                        echo "<p>$content</p>";
                    }

                    // Show the appropriate buttons
                    if($current_user_id == $user_id) {
                        echo "<div class='btn-group'>
                                <a href='single.php?post_id=$post_id' class='btn btn-success btn-sm'>View</a>
                                <a href='functions/delete_post.php?post_id=$post_id' class='btn btn-danger btn-sm'>Delete Post</a>
                              </div>";
                    } else {
                        echo "<div class='btn-group'>
                                <a href='single.php?post_id=$post_id' class='btn btn-success btn-sm'>View</a>
                              </div>";
                    }
                    echo "</div></div><br>";
                }
                ?>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>
