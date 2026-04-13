<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($conn, $get_user);

if (!$run_user) {
    die("Database query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_array($run_user);
if ($row) {
    $user_name = $row['user_name'];
    $user_id = $row['user_id'];
    $user_image = $row['user_image'];
    $user_cover = $row['user_cover'];
    $last_name = $row['l_name'];
    $first_name = $row['f_name'];
    $describe_user = $row['describe_user'];
    $Relationship_status = $row['Relationship'];
    $user_country = $row['user_country'];
    $register_date = $row['user_reg_date'];
    $user_gender = $row['user_gender'];
    $user_birthday = $row['user_birthday'];
} else {
    echo "User not found.";
    exit();
}

// Update cover photo logic
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

// Update profile image logic
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
        /* Background Animation */
        @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

        body {
            background: url('images/index_bg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: backgroundMove 40s linear infinite;
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

        #cover-img {
            height: auto;
            max-width: 100%;
            max-height: 410px;
            width: 100%;
            margin-bottom: -20px;
            
        }
        #update-cover-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 123, 255, 0.3); /* Semi-transparent background */
            padding: 8px 25px;
            border-radius: 4px;
            font-weight: bold;
            
        }
        #update-cover-button:hover {
            background-color: rgba(24, 237, 59, 0.6); /* Darken background on hover */
        }

        #profile-img {
            position: absolute;
            top: 250px;
            left: 30px;
            
        }
        
        #profile-img a {
            cursor: pointer; /* Change cursor to pointer on hover */
        }

        .img-circle {
            border-radius: 100%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-top: -30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 20px rgba(0,0,0,0.8);
            transition: transform 0.3s, box-shadow 0.3s; /* Add transition for smooth scaling and shadow */
            cursor:pointer;
        }

        .img-circle:hover {
            transform: scale(1.1); /* Scale up the image on hover */
            box-shadow: 0 0 30px rgba(0, 0, 0, 1); /* Increase shadow on hover */
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
        #update_profile:hover {
            background-color: rgba(221, 0, 255, 0.2);
        }
        #button_profile {
            position: absolute;
            top: 65%;
            left: 50%;
            cursor: pointer;
            transform: translate(-50%, -50%);
            transition: background-color 0.3s;
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
            margin-top: 30px;
            animation: fadeInUp 1s ease-in-out;
        }
        .button-container {
            text-align: right;
            margin-top: -5px;
            margin-bottom: 25px;
        }
        .date {
            text-align: right;
            margin-top: -20px;
            margin-left: 50px;
        }
        .profile-info {
            background-color: #e6e6e6;
            text-align: center;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            margin-left: 5px;
        }
        .profile-info h2 {
            margin-top: 10px;
        }
        .profile-info p {
            margin: 5px 5px;
        }

        .custom-btn {
            background-color: #46d3f2; /* Button background color */
            color: white; /* Text color */
            border: none; /* Remove border */
            transition: background-color 0.3s; /* Smooth transition */
            left: 5px;
            border-radius: 5px;
            position: relative;
            top: 5px;
            padding: 8px 12px;
        }

        .custom-btn:hover {
            background-color: #3ba1b8; /* Hover effect color */
            color: white; /* Keep text color white */
        }
    </style>
</head>


<body>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div>
                <div>
                    <img id="cover-img" class="img-rounded" src="cover/<?php echo $user_cover; ?>" alt="cover">
                </div>
                <form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
                    <ul class="nav pull-left" style="position:absolute;top:8px;left:25px;">
                        <li class="dropdown">
                            <button type="button" class="dropdown-toggle custom-btn" data-toggle="dropdown" onclick="toggleCoverUpload()">
                                Change Cover
                            </button>
                            <div class="dropdown-menu" id="coverUploadMenu" style="display:none;">
                                <center>
                                    <p></p><br><br>
                                    <label class="btn custom-btn">
                                        Select Cover Photo
                                        <input type="file" name="u_cover" size="60" id="coverInput" onchange="previewCoverImage(this)" style="display:none;" />
                                    </label><br><br>
                                    <img id="coverPreview" src="" alt="Cover Preview" style="display:none; max-width:100%; max-height:300px;">
                                    <button id="update-cover-button" type="submit" name="submit">Update Cover</button>
                                </center>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>

            <script>
                function toggleCoverUpload() {
                    const menu = document.getElementById("coverUploadMenu");
                    menu.style.display = menu.style.display === "none" ? "block" : "none";
                }

                function previewCoverImage(input) {
                    const file = input.files[0];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        document.getElementById('coverPreview').src = e.target.result; // Show preview
                        document.getElementById('coverPreview').style.display = "block"; // Display preview
                    }

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }
            </script>

            <div id="profile-img">
                <form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
                    <a href="user_profile.php?u_id=<?php echo $user_id; ?>">
                        <img id="profilePreview" src="users/<?php echo $user_image; ?>" alt="Profile" class="img-circle profile-img">
                    </a>
                    <label id="update_profile"> Select Profile
                        <input type="file" name="u_image" size="60" onchange="previewProfileImage(this)" style="display:none;" />
                    </label><br><br>
                    <button id="button_profile" name="update" class="btn btn-info btn-custom">Update Profile</button>
                </form>
            </div>

            <script>
                function previewProfileImage(input) {
                    var file = input.files[0];
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        document.getElementById('profilePreview').src = e.target.result; // Show preview
                    }

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }
            </script>

        </div>
    </div><br><br>
</div>
</body>
<div class="container">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2 profile-info">
            <h2><strong>About</strong></h2>
            <h4><strong><?php echo "$user_name $last_name"; ?></strong></h4>
            <p><strong><i style="color:grey;"><?php echo $describe_user; ?></i></strong></p>
            <p><strong>Relationship Status: </strong> <?php echo $Relationship_status; ?></p>
            <p><strong>Lives In: </strong> <?php echo $user_country; ?></p>
            <p><strong>Member Since: </strong> <?php echo $register_date; ?></p>
            <p><strong>Gender: </strong> <?php echo $user_gender; ?></p>
            <p><strong>Date of Birth: </strong> <?php echo $user_birthday; ?></p>
        </div>
        <div class="col-sm-6">
            
            
            <?php
            // Ensure $u_id is defined
            if (isset($_GET['u_id'])) {
                $u_id = $_GET['u_id'];
            } else {
                $u_id = $user_id; // Default to the logged-in user's ID if 'u_id' is not in the URL
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

                echo "<div id='own_posts'>
                    <div class='row'>
                        <div class='col-sm-2'>
                            <a href='user_profile.php?u_id=$user_id'><img src='users/$user_image' alt='Profile' class='img-circle profile-img'></a>
                        </div>
                        <div class='col-sm-6'>
                            <div class='date'>
                                <h3><a style='text-decoration:none; font-size:25px; cursor:pointer; color: #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                <h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                        </div>
                    </div>";

                if ($content == "No" && strlen($upload_image) >= 1) {
                    echo "<div class='row'>
                            <div class='col-sm-12'>
                                <img class='post-img' src='imagepost/$upload_image' alt='Post Image'>
                            </div>
                          </div><br>";
                } elseif (strlen($content) >= 1 && strlen($upload_image) >= 1) {
                    echo "<div class='row'>
                            <div class='col-sm-12'>
                                <p>$content</p>
                                <img class='post-img' src='imagepost/$upload_image' alt='Post Image'>
                            </div>
                          </div><br>";
                } else {
                    echo "<div class='row'>
                            <div class='col-sm-12'>
                                <h3><p>$content</p></h3>
                            </div>
                          </div><br>";
                }

                echo "<div class='button-container'>
                        <a href='single.php?post_id=$post_id' style='float:right;margin-right:5px;'><button class='btn btn-success btn-custom'>View</button></a>
                        <a href='edit_post.php?post_id=$post_id' style='float:right;margin-right:5px;'><button class='btn' style='background-color:#1789E1; color:#ffffff;transition: background-color 0.3s;'>Edit</button></a>
                        <a href='functions/delete_post.php?post_id=$post_id' class='btn btn-danger btn-custom' style='border-margin:5px; margin-right:5px;' onclick='return confirm(\"Are you sure you want to delete this post?\");'>Delete Post</a>

                      </div></div><br>";
            }
            ?>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
</body>
</html>
