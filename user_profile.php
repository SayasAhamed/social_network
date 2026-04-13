<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

// Secure database connection
include("includes/connection.php");

// Escape user input for SQL queries
function secure_input($input, $conn) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
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
            background-color: #f4f4f4;
            background: url('images/Background3.png') no-repeat center center fixed;
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

        .profile-info {
            background-color: #e6e6e6;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-img {
            border-radius: 50%; /* Makes the image circular */
            border: 3px solid #ddd; /* Border around the image */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 150px; /* Fixed width for the circular image */
            height: 150px; /* Fixed height for the circular image */
            object-fit: cover; /* Ensures the image covers the area of the circle */
            transition: transform 0.3s;
            cursor:pointer;
        }
        .profile-img:hover {
            transform: scale(1.1);
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
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        #own_posts {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .post-header img {
            border-radius: 50%; /* Circular image for post header */
            width: 100px; /* Size for the post header image */
            height: 100px; /* Size for the post header image */
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .post-img {
            max-width: 100%;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            margin-top:30px;
            transition: background-color 0.3s, transform 0.3s;
            border-radiuss: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-success:hover {
            background-color: #4caf50;
            transform: translateY(-2px);
        }
        .btn-danger:hover {
            background-color: #dc3545;
            transform: translateY(-2px);
        }

        /* User Image Hover Effect */
        .user-img {
            border-radius: 50%; /* Keeps the image circular */
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition */
        }

        .user-img:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
        }

        /* User Link Style */
        .user-link {
            float:right;
            margin-left:20px;
            text-decoration: none;
            color: #3897f0; /* Original color */
            transition: color 0.3s, text-decoration 0.3s; /* Transition for hover effect */
        }

        .user-link:hover {
            color: #0056b3; /* Darker color on hover */
            text-decoration: underline; /* Underline on hover */
        }

        /* Post Date Style */
        .post-date {
            color: #999; /* Lighter color for date */
        }

        .p6{
            animation: fadeInUp 1s ease-in-out;
        }

        </style>
</head>
<body>
    <div class="container">
        <?php 
        if (isset($_GET['u_id'])) {
            $u_id = secure_input($_GET['u_id'], $conn);
            if (!is_numeric($u_id) || $u_id <= 0) {
                echo "<script>window.open('home.php', '_self')</script>";
                exit();
            }
        ?>
        <div class="row p6">
            <div class="col-sm-3">
                <?php
                if (isset($_GET['u_id'])) {
                    $user_id = secure_input($_GET['u_id'], $conn);

                    $select = "SELECT * FROM users WHERE user_id = '$user_id'";
                    $run = mysqli_query($conn, $select);

                    if ($run && mysqli_num_rows($run) > 0) {
                        $row = mysqli_fetch_array($run);

                        $id = $row['user_id'];
                        $image = htmlspecialchars($row['user_image']);
                        $name = htmlspecialchars($row['user_name']);
                        $f_name = htmlspecialchars($row['f_name']);
                        $l_name = htmlspecialchars($row['l_name']);
                        $describe_user = htmlspecialchars($row['describe_user']);
                        $relationship = htmlspecialchars($row['Relationship']);
                        $country = htmlspecialchars($row['user_country']);
                        $gender = htmlspecialchars($row['user_gender']);
                        $u_birthday = htmlspecialchars($row['user_birthday']);
                        $register_date = htmlspecialchars($row['user_reg_date']);
                        $status = htmlspecialchars($row['status']);

                        echo "
                            <div class='profile-info'>
                                <h2>Information about</h2>
                                <a href='profile.php?u_id=$user_id'>
                                    <img src='users/$image' class='profile-img'>
                                </a>
                                <br><br>
                                <ul class='info-list'>
                                    <li><strong>Username:</strong> $f_name $l_name</li>
                                    <li><strong>Status:</strong> <span style='color: gray;'>$describe_user</span></li>
                                    <li><strong>Gender:</strong> <span style='color: gray;'>$gender</span></li>
                                    <li><strong>Country:</strong> <span style='color: gray;'>$country</span></li>
                                    <li><strong>Relationship:</strong> <span style='color: gray;'>$relationship</span></li>
                                    <li><strong>Registered:</strong> <span style='color: gray;'>$register_date</span></li>
                                    <li><strong>Birthday:</strong> <span style='color: gray;'>$u_birthday</span></li>
                                    <li><strong>Status:</strong> <span style='color: gray;'>$status</span></li>
                                </ul>";

                        $user = $_SESSION['user_email'];
                        $user = secure_input($user, $conn);
                        $get_user =  "SELECT * FROM users WHERE user_email = '$user'";
                        $run_user = mysqli_query($conn, $get_user);
                        $row = mysqli_fetch_array($run_user);

                        $userown_id = $row['user_id'];

                        if ($user_id == $userown_id) {
                            echo "<a href='edit_profile.php?u_id=$userown_id' class='btn btn-success'>Edit Profile</a><br><br>";
                        }

                        echo "</div>";
                    } else {
                        echo "<script>alert('User not found!'); window.open('home.php', '_self');</script>";
                        exit();
                    }
                }
                ?>
            </div>
            <div class="col-sm-9">
                <center><h1><strong><?php echo "$f_name $l_name";?></strong>'s Posts</h1></center>
                <?php
                if (isset($_GET['u_id'])) {
                    $u_id = secure_input($_GET['u_id'], $conn);
                }

                $get_posts = "SELECT * FROM posts WHERE user_id='$u_id' ORDER BY 1 DESC LIMIT 5";
                $run_post = mysqli_query($conn, $get_posts);

                $current_user_email = $_SESSION['user_email'];
                $current_user_email = secure_input($current_user_email, $conn);
                $get_current_user = "SELECT * FROM users WHERE user_email = '$current_user_email'";
                $run_current_user = mysqli_query($conn, $get_current_user);
                $current_user_row = mysqli_fetch_array($run_current_user);
                $current_user_id = $current_user_row['user_id'];

                while ($row_posts = mysqli_fetch_array($run_post)) {
                    $post_id = htmlspecialchars($row_posts['post_id']);
                    $user_id = htmlspecialchars($row_posts['user_id']);
                    $content = htmlspecialchars($row_posts['post_content']);
                    $upload_image = htmlspecialchars($row_posts['upload_image']);
                    $post_date = htmlspecialchars($row_posts['post_date']);

                    $user = "SELECT * FROM users WHERE user_id = '$user_id' AND posts='yes'";
                    $run_user = mysqli_query($conn, $user);
                    $row_user = mysqli_fetch_array($run_user);

                    $user_name = htmlspecialchars($row_user['user_name']);
                    $f_name = htmlspecialchars($row_user['f_name']);
                    $l_name = htmlspecialchars($row_user['l_name']);
                    $user_image = htmlspecialchars($row_user['user_image']);

                    echo "<div id='own_posts'>";
                    echo "<div class='post-header'>
                            <a href='profile.php?u_id=$user_id'><img src='users/$user_image' class='profile-img'>
                            <div>
                                <h3><a href='profile.php?u_id=$user_id' style='text-decoration:none; color:#3897f0;'>$user_name</a></h3>
                                <h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                          </div>";

                    echo "<div class='post-content'>";
                    if ($content == "No" && strlen($upload_image) >= 1) {
                        echo "<img src='imagepost/$upload_image' class='post-img'>";
                    } elseif (strlen($content) >= 1 && strlen($upload_image) >= 1) {
                        echo "<p float:left;margin-left:10px;font-weight:bold;>$content</p>
                              <img src='imagepost/$upload_image' class='post-img'>";
                    } else {
                        echo "<p float:left;margin-left:10px;font-weight:bold;>$content</p>";
                    }

                    if ($current_user_id == $user_id) {
                        echo "<div><div class='btn-group'>
                                <a href='single.php?post_id=$post_id' class='btn btn-success btn-sm'style='margin-right:10px;'>View</a>
                                <a href='edit_post.php?post_id=$post_id'><button class='btn btn-sm' style='margin-left:10px; background-color:#1789E1; color:#ffffff; transition: background-color 0.3s;'>Edit</button></a>
                                <a href='functions/delete_post.php?post_id=$post_id' class='btn btn-danger btn-sm' style='border-margin:5px;' onclick='return confirm(\"Are you sure you want to delete this post?\");'>Delete Post</a>

                              </div> </div>";
                    } else {
                        echo "<div><div class='btn-group'>
                                <a href='single.php?post_id=$post_id' class='btn btn-success btn-sm'>View</a>
                              </div> </div>";
                    }
                    echo "</div></div><br>";
                }
                ?>
            </div>
        </div>
        <?php } ?>
    </div>    </div>
</body>
</html>
