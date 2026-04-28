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
    <title>Edit Post</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<body>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php
            if (isset($_GET['post_id'])) {
                $post_id = $_GET['post_id'];
                
                $get_post_query = "SELECT * FROM posts WHERE post_id='$post_id'";
                $run_post = mysqli_query($conn, $get_post_query);
                if ($run_post) {
                    $row = mysqli_fetch_array($run_post);
                    $post_content = $row['post_content'];
                } else {
                    echo "<script>alert('Post not found!'); window.open('home.php', '_self');</script>";
                }
            }
            ?>
            <form action="" method="post" id="f">
                <center><h2>Edit Your Post:</h2></center><br>
                <textarea class="form-control" cols="83" rows="4" name="content"><?php echo htmlspecialchars($post_content); ?></textarea><br>
                <input type="submit" name="update" value="Update Post" class="btn btn-info"/>
            </form>

            <?php
            if (isset($_POST['update'])) {
                $content = $_POST['content'];
                $update_post_query = "UPDATE posts SET post_content='$content' WHERE post_id='$post_id'";
                $run_update = mysqli_query($conn, $update_post_query);

                if ($run_update) {
                    echo "<script>alert('Your Post Updated'); window.open('home.php', '_self');</script>";
                } else {
                    echo "<script>alert('Update failed');</script>";
                }
            }
            ?>
        </div>
        <div class="col-sm-3"></div>
    </div> 
</body>
</html>
