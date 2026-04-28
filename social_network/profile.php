<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
}

$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($conn, $get_user);
$row = mysqli_fetch_array($run_user);

$user_name = $row['user_name'];
$user_id = $row['user_id'];
$user_image = $row['user_image'];
$user_cover = $row['user_cover'];

if (isset($_POST['submit'])) {
    $u_cover = $_FILES['u_cover']['name'];
    $image_tmp = $_FILES['u_cover']['tmp_name'];

    if ($u_cover == '') {
        echo "<script>alert('Please Select Cover Image')</script>";
        echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        exit();
    } else {
        move_uploaded_file($image_tmp, "cover/$u_cover");
        $update = "UPDATE users SET user_cover='$u_cover' WHERE user_id='$user_id'";
        $run = mysqli_query($conn, $update);
        if ($run) {
            echo "<script>alert('Your Cover Updated')</script>";
            echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        }
    }
}

if (isset($_POST['update'])) {
    $u_image = $_FILES['u_image']['name'];
    $image_tmp = $_FILES['u_image']['tmp_name'];

    if ($u_image == '') {
        echo "<script>alert('Please Select Profile Image')</script>";
        echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        exit();
    } else {
        move_uploaded_file($image_tmp, "users/$u_image");
        $update = "UPDATE users SET user_image='$u_image' WHERE user_id='$user_id'";
        $run = mysqli_query($conn, $update);
        if ($run) {
            echo "<script>alert('Your Profile Updated')</script>";
            echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user_name; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        #cover-img {
            height: auto;
            max-height: 400px;
            width: 100%;
            margin-bottom: -20px;
        }
        #profile-img {
            position: absolute;
            top: 210px;
            left: 40px;
        }
        .img-circle {
            border-radius: 100%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-top: -30px;
            border: 3px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #update_profile {
            position: relative;
            top: -26px;
            cursor: pointer;
            left: 60px;
            border-radius: 1px;
            background-color: rgba(0, 0, 0, 0.05);
            transform: translate(-50%, -50%);
        }
        #button_profile {
            position: absolute;
            top: 65%;
            left: 50%;
            cursor: pointer;
            transform: translate(-50%, -50%);
        }
        #own_posts {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .post-img {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .row {
            margin-top: 20px;
        }
        .button-container {
            text-align: right;
            margin-top: -10px;
        }
        .date {
            text-align: right;
            margin-top: -20px;
            margin-left: 50px;
        }
        .profile-info {
            background-color: #e6e6e6;
            text-align: center;
            border-radius: 7px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .profile-info h2 {
            margin-top: 0;
        }
        .profile-info p {
            margin: 10px 0;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div>
                <div><img id="cover-img" class="img-rounded" src="cover/<?php echo $user_cover; ?>" alt="cover"></div>
                <form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
                    <ul class="nav pull-left" style="position:absolute;top:8px;left:25px;">
                        <li class="dropdown">
                            <button class="dropdown-toggle btn btn-default" data-toggle="dropdown">Change Cover</button>
                            <div class="dropdown-menu">
                                <center>
                                    <p>Click <strong>Select Cover</strong> and then click the <br> <strong>Update Cover</strong></p>
                                    <label class="btn btn-info"> Select Cover Photo
                                        <input type="file" name="u_cover" size="60"/>
                                    </label><br><br>
                                    <button name="submit" class="btn btn-info">Update Cover</button>
                                </center>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
            <div id="profile-img">
                <img src="users/<?php echo $user_image; ?>" alt="Profile" class="img-circle profile-img">
                <form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
                    <label id="update_profile"> Select Profile
                        <input type="file" name="u_image" size="60"/>
                    </label><br><br>
                    <button id="button_profile" name="update" class="btn btn-info">Update Profile</button>
                </form>
            </div><br>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2 profile-info">
            <center><h2><strong>About</strong></h2></center>
            <center><h4><strong><?php echo "$first_name $last_name"; ?></strong></h4></center>
            <p><strong><i style="color:grey;"><?php echo $describe_user; ?></i></strong></p>
            <p><strong>Relationship Status: </strong> <?php echo $Relationship_status; ?></p>
            <p><strong>Lives In: </strong> <?php echo $user_country; ?></p>
            <p><strong>Member Since: </strong> <?php echo $register_date; ?></p>
            <p><strong>Gender: </strong> <?php echo $user_gender; ?></p>
            <p><strong>Date of Birth: </strong> <?php echo $user_birthday; ?></p>
        </div>
        <div class="col-sm-6">
            <!-- Display user posts -->
            <?php
            if (isset($_GET['u_id'])) {
                $u_id = $_GET['u_id'];
            }

            $get_posts = "SELECT * FROM posts WHERE user_id='$u_id' ORDER BY 1 DESC LIMIT 5";
            $run_posts = mysqli_query($conn, $get_posts);

            while ($row_posts = mysqli_fetch_array($run_posts)) {
                $post_id = $row_posts['post_id'];
                $user_id = $row_posts['user_id'];
                $content = $row_posts['post_content'];
                $upload_image = $row_posts['upload_image'];
                $post_date = $row_posts['post_date'];

                $user = "SELECT * FROM users WHERE user_id='$user_id' AND posts='yes'";
                $run_user = mysqli_query($conn, $user);
                $row_user = mysqli_fetch_array($run_user);

                $user_name = $row_user['user_name'];
                $user_image = $row_user['user_image'];

                if ($content == "No" && strlen($upload_image) >= 1) {
                    echo "
                    <div id='own_posts'>
                        <div class='row'>
                            <div class='col-sm-2'>
                                <img src='users/$user_image' alt='Profile' class='img-circle profile-img'>
                            </div>
                            <div class='col-sm-6'>
                                <div class='date'>
                                    <h3><a style='text-decoration:none; font-size:35px; cursor:pointer; color: #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                    <h4><small style='color:black;margin-right:10px;'>Updated a post on <strong>$post_date</strong></small></h4>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <img class='post-img' src='imagepost/$upload_image' alt='Post Image'>
                            </div>
                        </div><br>
                        <div class='button-container'>
                            <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-success'>View</button></a>
                            <a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div><br>";
                } elseif (strlen($content) >= 1 && strlen($upload_image) >= 1) {
                    echo "
                    <div id='own_posts'>
                        <div class='row'>
                            <div class='col-sm-2'>
                                <img src='users/$user_image' alt='Profile' class='img-circle profile-img'>
                            </div>
                            <div class='col-sm-6'>
                                <div class='date'>
                                    <h3><a style='text-decoration:none; font-size:35px; cursor:pointer; color: #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                    <h4><small style='color:black;margin-right:10px;'>Updated a post on <strong>$post_date</strong></small></h4>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <p>$content</p>
                                <img class='post-img' src='imagepost/$upload_image' alt='Post Image'>
                            </div>
                        </div><br>
                        <div class='button-container'>
                            <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-success'>View</button></a>
                            <a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div><br>";
                } else {
                    echo "
                    <div id='own_posts'>
                        <div class='row'>
                            <div class='col-sm-2'>
                                <img src='users/$user_image' alt='Profile' class='img-circle profile-img'>
                            </div>
                            <div class='col-sm-6'>
                                <div class='date'>
                                    <h3><a style='text-decoration:none; font-size:35px; cursor:pointer; color: #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                    <h4><small style='color:black;margin-right:10px;'>Updated a post on <strong>$post_date</strong></small></h4>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-2'></div>
                            <div class='col-sm-6'>
                                <h3><p>$content</p></h3>
                            </div>
                            <div class='col-sm-4'></div>
                        </div><br>
                        <div class='button-container'>
                            <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-success'>View</button></a>
                            <a href='edit_post.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Edit</button></a>
                            <a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div><br>";
                }
            }
            ?>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
</body>
</html>
