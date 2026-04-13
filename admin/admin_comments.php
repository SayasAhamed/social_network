<?php

include("../includes/connection.php");

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: signin.php");
    exit();
}

// Fetch all comments
$comments_query = "SELECT * FROM comments";
$comments_result = mysqli_query($conn, $comments_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Comments</title>
    <link rel="stylesheet" href="admin_includes/navbar.css">
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 2rem;
            color: #333;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
        }

        h3 {
            font-size: 1.5rem;
            margin: 20px 0;
            color: #2196f3;
        }

        .comment-table-container {
            width: 90%;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
            overflow: hidden;
        }

        .user-profile-header {
            background-color: #2196f3;
            padding: 10px 20px;
            color: white;
            font-weight: bold;
            text-align: left;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .user-profile-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .user-profile-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background-color: #2196f3;
            color: #fff;
            font-weight: bold;
        }

        td {
            color: #000;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .action-buttons {
            
            justify-content: center;
            gap: 15px;
        }

        .action-buttons a {
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .action-buttons a:hover {
            transform: scale(1.1);
        }

        .action-buttons a:active {
            transform: scale(1);
        }

        .no-comments-message {
            text-align: center;
            font-size: 1.1rem;
            color: #888;
        }

        /* Table row animation */
        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        tr {
            animation: fadeInRow 0.5s ease-out;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .scrollable-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <h2 style='color:#fff;'>All Comments</h2>

    <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
        <?php
        // Fetch user details for each comment
        $user_query = "SELECT * FROM users WHERE user_id = " . $comment['user_id'];
        $user_result = mysqli_query($conn, $user_query);
        $user = mysqli_fetch_assoc($user_result);
        
        // Fetch post details for the comment
        $post_query = "SELECT * FROM posts WHERE post_id = " . $comment['post_id'];
        $post_result = mysqli_query($conn, $post_query);
        $post = mysqli_fetch_assoc($post_result);
        ?>

        <div class="comment-table-container">
            <div class="user-profile-header">
                <!-- Display User Profile Image and Name -->
                <img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="User Profile Image">
                <h3><?php echo htmlspecialchars($user['user_name']); ?></h3>
            </div>

            <div class="scrollable-container">
                <table>
                    <tr>
                        <th>Comment</th>
                        <th>Commented To</th> <!-- New column for Commented To -->
                        <th>Actions</th>
                        <th>Date</th>
                    </tr>
                    <tr>
                        <td style='font-size:13px;'><strong><?php echo htmlspecialchars($comment['comment']); ?></strong></td>
                        <td>
                            <!-- Display Post Image and Title -->
                            <img src="../imagepost/<?php echo htmlspecialchars($post['upload_image']); ?>" alt="Post Image" style="width: 60px; height: 60px; border-radius: 5px; object-fit: cover;">
                            <br>
                            <a href="view_post.php?id=<?php echo $post['post_id']; ?>" style="color: #2196f3; font-size: 12px;"><?php echo htmlspecialchars($post['post_content']); ?></a>
                        </td>
                        <td class="action-buttons">
                            <a class='btn btn-info' href="view_post.php?id=<?php echo $comment['post_id']; ?>">View Post</a> 
                            <a class='btn btn-danger' href="view_post.php?id=<?php echo $comment['post_id']; ?>&delete_comment_id=<?php echo $comment['com_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                        <td><?php echo htmlspecialchars($comment['date']); ?></td>
                    </tr>
                </table>
            </div>
        </div>

    <?php endwhile; ?>

</body>
</html>
