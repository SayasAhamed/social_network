<?php
include("../includes/connection.php");
include("admin_includes/adminheader.php");

// Delete a post if delete_post_id is set in the URL
if (isset($_GET['delete_post_id'])) {
    $delete_post_id = intval($_GET['delete_post_id']);
    $delete_post_query = "DELETE FROM posts WHERE post_id = ?";
    $delete_post_stmt = mysqli_prepare($conn, $delete_post_query);
    mysqli_stmt_bind_param($delete_post_stmt, "i", $delete_post_id);
    mysqli_stmt_execute($delete_post_stmt);

    // Redirect with success parameter
    header("Location: view_profile.php?u_id=" . intval($_GET['u_id']) . "&delete=post_success");
    exit;
}

// Delete a comment if delete_comment_id is set in the URL
if (isset($_GET['delete_comment_id'])) {
    $delete_comment_id = intval($_GET['delete_comment_id']);
    $delete_comment_query = "DELETE FROM comments WHERE com_id = ?";
    $delete_comment_stmt = mysqli_prepare($conn, $delete_comment_query);
    mysqli_stmt_bind_param($delete_comment_stmt, "i", $delete_comment_id);
    mysqli_stmt_execute($delete_comment_stmt);

    // Redirect with success parameter
    header("Location: view_profile.php?u_id=" . intval($_GET['u_id']) . "&delete=comment_success");
    exit;
}

// Check if the user ID is set in the URL
if (isset($_GET['u_id'])) {
    $user_id = intval($_GET['u_id']); // Get the user ID from the URL and ensure it's an integer

    // Fetch user details
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($user_result);

    // Fetch user posts
    $post_query = "SELECT * FROM posts WHERE user_id = ? ORDER BY post_date DESC";
    $post_stmt = mysqli_prepare($conn, $post_query);
    mysqli_stmt_bind_param($post_stmt, "i", $user_id);
    mysqli_stmt_execute($post_stmt);
    $post_result = mysqli_stmt_get_result($post_stmt);

    // Fetch user comments with associated post images
    $comment_query = "
        SELECT comments.*, posts.upload_image 
        FROM comments 
        JOIN posts ON comments.post_id = posts.post_id 
        WHERE comments.user_id = ? 
        ORDER BY comments.date DESC";
    $comment_stmt = mysqli_prepare($conn, $comment_query);
    mysqli_stmt_bind_param($comment_stmt, "i", $user_id);
    mysqli_stmt_execute($comment_stmt);
    $comment_result = mysqli_stmt_get_result($comment_stmt);

} else {
    echo "<p>No user ID specified.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>View User Profile - <?php echo htmlspecialchars($user['user_name']); ?></title>
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
        .profile-section, .posts-section, .comments-section { 
            margin-top: 30px; 
        }
        .profile-section img { 
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            object-fit: cover; 
        }
        .post, .comment { 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            margin-top: 15px; 
            background-color: #fafafa; 
        }
        .post h4, .comment h5 { 
            color: #2196f3; 
        }
        .comment img { 
            width: 50px; 
            height: 50px; 
            object-fit: cover; 
            border-radius: 8px; 
            margin-right: 10px; 
        }
        footer { 
            text-align: center; 
            padding: 10px; 
            background-color: rgba(0, 0, 0, 0.8); 
            color: #f0f0f0; 
            margin-top: 50px; 
        }
        .delete-btn { 
            color: #d9534f; 
            text-decoration: none; 
            font-size: 14px; 
        }
            /* Style for the floating back button */
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2196f3;
            color: #fff;
            padding: 10px 20px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        
        .floating-btn a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .floating-btn:hover {
            background-color: #0d8be0;
        }

    </style>
    <script>
        // Display success alert if delete parameter is present, then remove it from the URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const deleteType = urlParams.get('delete');

            if (deleteType) {
                if (deleteType === 'post_success') {
                    alert("Post successfully deleted!");
                } else if (deleteType === 'comment_success') {
                    alert("Comment successfully deleted!");
                }

                // Remove the 'delete' parameter from the URL without reloading the page
                urlParams.delete('delete');
                const newUrl = window.location.pathname + '?' + urlParams.toString();
                window.history.replaceState({}, document.title, newUrl);
            }
        };
    </script>
</head>
<body>

<div class="container">
    <!-- Profile Section -->
    <div class="profile-section text-center">
        <img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($user['user_name']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['user_email']); ?></p>
        <p>From: <?php echo htmlspecialchars($user['user_country']); ?></p>
        <p>Gender: <?php echo htmlspecialchars($user['user_gender']); ?></p>
        <p>BirthDay: <?php echo htmlspecialchars($user['user_birthday']); ?></p>
        <p>Relationship: <?php echo htmlspecialchars($user['Relationship']); ?></p>
        <p>Bio: <?php echo htmlspecialchars($user['describe_user']); ?></p>
        <p>Joined: <?php echo date("F j, Y", strtotime($user['user_reg_date'])); ?></p>
    </div>

    <!-- Posts Section -->
    <div class="posts-section">
        <h3>User Posts</h3>
        <?php while ($post = mysqli_fetch_assoc($post_result)) { ?>
            <div class="post">
                <?php if (!empty($post['upload_image'])): ?>
                    <img src="../imagepost/<?php echo htmlspecialchars($post['upload_image']); ?>" alt="Post Image" style="max-width:100%; height:auto; margin-bottom: 10px;">
                <?php endif; ?>
                <h4><?php echo htmlspecialchars($post['post_content']); ?></h4>
                <small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($post['post_date'])); ?></small>
                <br>
                <a href="view_profile.php?u_id=<?php echo $user_id; ?>&delete_post_id=<?php echo $post['post_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post</a>
            </div>
        <?php } ?>
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
        <h3>User Comments</h3>
        <?php while ($comment = mysqli_fetch_assoc($comment_result)) { ?>
            <div class="comment">
                <div style="display: flex; align-items: center;">
                    <?php if (!empty($comment['upload_image'])): ?>
                        <img src="../imagepost/<?php echo htmlspecialchars($comment['upload_image']); ?>" alt="Commented Post Image">
                    <?php endif; ?>
                    <div>
                        <h5>Comment on Post ID <?php echo $comment['post_id']; ?></h5>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        <small>Commented on: <?php echo date("F j, Y, g:i a", strtotime($comment['date'])); ?></small>
                        <br>
                        <a href="view_profile.php?u_id=<?php echo $user_id; ?>&delete_comment_id=<?php echo $comment['com_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this comment?');">Delete Comment</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="floating-btn">
    <a href="admin_users.php?">Back to Users</a>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Social Hub Network's Admin Dashboard</p>
</footer>

</body>
</html>
