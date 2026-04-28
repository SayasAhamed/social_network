<?php
session_start();
include("includes/header.php");
include("includes/connection.php");

$user_email = $_SESSION['user_email'];

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
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
        
        #newImageSection {
            display: none;
        }
        #preview {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            display: none;
        }
        /* Hide the actual file input */
        #upload_image {
            display: none;
        }
        .p6{
            animation: fadeInUp 1s ease-in-out;
        }
    </style>
    <script>
            function showImageUpload() {
                document.getElementById("newImageSection").style.display = "block";
            }

            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById("preview");
                    output.src = reader.result;
                    output.style.display = "block";
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            function triggerFileInput() {
                document.getElementById("upload_image").click();
            }
    </script>
</head>
<body>
    <div class="row p6">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php
            if (isset($_GET['post_id'])) {
                $post_id = $_GET['post_id'];
                
                $get_post_query = "SELECT * FROM posts WHERE post_id='$post_id'";
                $run_post = mysqli_query($conn, $get_post_query);
                
                if ($run_post && mysqli_num_rows($run_post) > 0) {
                    $row = mysqli_fetch_array($run_post);
                    $post_content = $row['post_content'];
                    $current_image = $row['upload_image'];
                } else {
                    echo "<script>alert('Post not found!'); window.open('home.php', '_self');</script>";
                    exit();
                }
            }
            ?>
            <div>
                <form action="" method="post" enctype="multipart/form-data" id="f">
                    <center><h2>Edit Your Post:</h2></center><br>
                    <textarea class="form-control" cols="83" rows="4" name="content"><?php echo htmlspecialchars($post_content); ?></textarea><br>
            </div>
                <?php if (!empty($current_image)) { ?>
                    <p>Current Image:</p>
                    <img src="imagepost/<?php echo $current_image; ?>" alt="Post Image" style="max-width:100%; height:auto;"><br><br>
                    <button type="button" class="btn btn-primary" onclick="showImageUpload()">Upload New Image</button><br><br>
                <?php } ?>

                <div id="newImageSection">
                    <button type="button" class="btn btn-success " onclick="triggerFileInput()">Select a new image</button>
                    <input type="file" id="upload_image" name="upload_image" class="form-control" onchange="previewImage(event)">
                    <br>
                    <img id="preview" alt="New Image Preview"><br>
                    <button type="submit" name="replace_image" class="btn btn-warning">Replace Image</button>
                </div>

                <input type="submit" name="update" value="Update Post" class="btn btn-info"/>
            </form>
            <?php
            // Check if 'update' button was clicked
            if (isset($_POST['update'])) {
                $updated_content = mysqli_real_escape_string($conn, $_POST['content']);

                // Update the post content in the database
                $update_content_query = "UPDATE posts SET post_content='$updated_content' WHERE post_id='$post_id'";
                $run_update_content = mysqli_query($conn, $update_content_query);

                if ($run_update_content) {
                    echo "<script>alert('Post updated successfully'); window.open('home.php', '_self');</script>";
                } else {
                    echo "<script>alert('Failed to update post content');</script>";
                }
            }
            ?>

            <?php
                if (isset($_POST['replace_image'])) {
                    $image_name = $_FILES['upload_image']['name'];
                    $image_tmp = $_FILES['upload_image']['tmp_name'];
                    $random_number = rand(1, 100);
                
                    if (!empty($image_name)) {
                        $target_image_path = "imagepost/$image_name.$random_number";
                        
                        if (move_uploaded_file($image_tmp, $target_image_path)) {
                            $update_image_query = "UPDATE posts SET upload_image='$image_name.$random_number' WHERE post_id='$post_id'";
                            $run_update_image = mysqli_query($conn, $update_image_query);
                
                            if ($run_update_image) {
                                echo "<script>alert('Image replaced successfully'); window.open('edit_post.php?post_id=$post_id', '_self');</script>";
                            } else {
                                echo "<script>alert('Database update failed');</script>";
                            }
                        } else {
                            echo "<script>alert('Image upload failed');</script>";
                        }
                    } else {
                        echo "<script>alert('Please select an image');</script>";
                    }
                }
                
            ?>
        </div>
        <div class="col-sm-3"></div>
    </div> 
</body>
</html>

