<?php
include("../includes/connection.php");
include("admin_includes/adminheader.php");

// Check if post ID is set in the URL
if (!isset($_GET['id'])) {
    echo "No post ID specified.";
    exit;
}

$post_id = intval($_GET['id']); // Get the post ID from the URL

// Fetch post details
$post_query = "SELECT posts.*, users.user_name, users.user_image FROM posts 
               JOIN users ON posts.user_id = users.user_id 
               WHERE posts.post_id = ?";
$post_stmt = mysqli_prepare($conn, $post_query);
mysqli_stmt_bind_param($post_stmt, "i", $post_id);
mysqli_stmt_execute($post_stmt);
$post_result = mysqli_stmt_get_result($post_stmt);
$post = mysqli_fetch_assoc($post_result);

// Fetch comments for the post
$comment_query = "SELECT * FROM comments WHERE post_id = ? ORDER BY date DESC";
$comment_stmt = mysqli_prepare($conn, $comment_query);
mysqli_stmt_bind_param($comment_stmt, "i", $post_id);
mysqli_stmt_execute($comment_stmt);
$comments_result = mysqli_stmt_get_result($comment_stmt);

// Delete a specific comment if delete_comment_id is set
if (isset($_GET['delete_comment_id'])) {
    $delete_comment_id = intval($_GET['delete_comment_id']);
    $delete_comment_query = "DELETE FROM comments WHERE com_id = ?";
    $delete_comment_stmt = mysqli_prepare($conn, $delete_comment_query);
    mysqli_stmt_bind_param($delete_comment_stmt, "i", $delete_comment_id);
    mysqli_stmt_execute($delete_comment_stmt);

    // Redirect with success message via URL
    header("Location: view_post.php?id=" . $post_id . "&delete=comment_success");
    exit;
}

// Delete post and its comments if delete_post_id is set
if (isset($_GET['delete_post_id'])) {
    // Get post details first
    $post_query = "SELECT upload_image FROM posts WHERE post_id = ?";
    $post_stmt = mysqli_prepare($conn, $post_query);
    mysqli_stmt_bind_param($post_stmt, "i", $post_id);
    mysqli_stmt_execute($post_stmt);
    $post_result = mysqli_stmt_get_result($post_stmt);
    $post_details = mysqli_fetch_assoc($post_result);

    // If there is an image associated with the post, delete it
    if (!empty($post_details['upload_image'])) {
        $image_path = "../imagepost/" . $post_details['upload_image'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Now delete the post and comments
    $delete_post_query = "DELETE FROM posts WHERE post_id = ?";
    $delete_comment_query = "DELETE FROM comments WHERE post_id = ?";
    
    $delete_post_stmt = mysqli_prepare($conn, $delete_post_query);
    mysqli_stmt_bind_param($delete_post_stmt, "i", $post_id);
    mysqli_stmt_execute($delete_post_stmt);

    $delete_comment_stmt = mysqli_prepare($conn, $delete_comment_query);
    mysqli_stmt_bind_param($delete_comment_stmt, "i", $post_id);
    mysqli_stmt_execute($delete_comment_stmt);

    // Redirect with success message via URL
    header("Location: admin.php?page=posts&id=" . $post_id . "&delete=post_success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {  
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            color: #333; 
        }
        .container { 
            max-width: 800px; 
            margin: auto; 
            padding: 20px; 
            background-color: #fff; 
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1); 
        }
        .profile-section img { 
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            object-fit: cover; 
        }
        .post-section, .comment-section { 
            margin-top: 20px; 
        }
        .comment { 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            margin-top: 10px; 
            background-color: #fafafa; 
        }
        .floating-btn { 
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            background-color: #2196f3; 
            color: #fff; 
            padding: 10px 20px; 
            border-radius: 30px; 
            text-align: center; 
        }
        .floating-btn a { 
            color: #fff; 
            text-decoration: none; 
            font-weight: bold; 
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Black background */
            background-color: rgba(0, 0, 0, 0.4); /* Black with transparency */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        /* Close Button */
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            top: 5px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('delete')) {
                const deleteType = urlParams.get('delete');
                const modal = document.getElementById("successModal");
                const modalContent = document.getElementById("modalMessage");

                if (deleteType === 'post_success') {
                    modalContent.innerHTML = "Post and associated comments successfully deleted!";
                    modal.style.display = "block"; // Show the modal
                    setTimeout(function() {
                        window.location.href = 'admin.php?page=posts'; // Redirect after modal close
                    }, 2000); // Redirect after 2 seconds
                } else if (deleteType === 'comment_success') {
                    modalContent.innerHTML = "Comment successfully deleted!";
                    modal.style.display = "block"; // Show the modal
                    setTimeout(function() {
                        window.location.href = 'view_post.php?id=<?php echo $post_id; ?>'; // Redirect back to the post after modal close
                    }, 2000); // Redirect after 2 seconds
                }
            }
        };

        // Close the modal when user clicks the close button
        function closeModal() {
            const modal = document.getElementById("successModal");
            modal.style.display = "none";
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Display Post and User Info -->
    <div class="post-section">
        <h2><?php echo htmlspecialchars($post['post_content']); ?></h2>
        <?php if (!empty($post['upload_image'])): ?>
            <img src="../imagepost/<?php echo htmlspecialchars($post['upload_image']); ?>" style="max-width:100%; height:auto;">
        <?php endif; ?>
        <p>Posted by: <strong><?php echo htmlspecialchars($post['user_name']); ?></strong></p>
        <p>Date: <?php echo htmlspecialchars($post['post_date']); ?></p>
        <a href="view_post.php?id=<?php echo $post_id; ?>&delete_post_id=<?php echo $post_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post and all its comments?');">Delete Post</a>
    </div>

    <!-- Display Comments -->
    <div class="comment-section">
        <h3>Comments</h3>
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <div class="comment">
                    <p style="font-size:20px;"><strong><?php echo htmlspecialchars($comment['comment']); ?></strong></p>
                    <small><?php echo date("F j, Y, g:i a", strtotime($comment['date'])); ?></small>
                    <br>
                    <!-- Delete Comment Button -->
                    <a href="view_post.php?id=<?php echo $post_id; ?>&delete_comment_id=<?php echo $comment['com_id']; ?>" class="btn btn-danger" style="border:10px;" onclick="return confirm('Are you sure?')">Delete</a>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments for this post.</p>
        <?php endif; ?>
    </div>

    <!-- Floating button to return to admin page -->
    <div class="floating-btn">
        <a href="admin.php?page=posts">Back to Posts</a>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="modalMessage"></p>
    </div>
</div>

</body>
</html>
