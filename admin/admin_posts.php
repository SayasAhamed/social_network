<?php

include("../includes/connection.php");


// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>All Users' Posts</title>
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

        .user-table-container {
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
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-buttons a {
            padding: 10px 15px;
            font-size: 10px;
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

        .no-posts-message {
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

    <h2 style="color:#fff;">All Users' Posts</h2>

    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
        <div class="user-table-container">
            <div class="user-profile-header">
                <!-- Display User Profile Image and Name -->
                <img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="User Profile Image">
                <h3><?php echo htmlspecialchars($user['user_name']); ?></h3>
            </div>

            <?php
            // Fetch posts for the current user
            $posts_query = "SELECT * FROM posts WHERE user_id = " . $user['user_id'];
            $posts_result = mysqli_query($conn, $posts_query);
            
            if (mysqli_num_rows($posts_result) > 0): ?>
                <div class="scrollable-container">
                    <table>
                        <tr>
                            <th>Content</th>
                            <th>Actions</th>
                            <th>Post Date</th>
                        </tr>
                        <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['post_content']); ?></td>
                                <td class="action-buttons">
                                    <a class='btn btn-success' href="view_post.php?id=<?php echo $post['post_id']; ?>">View Post</a> <br>
                                    
                                    <a class='btn btn-danger ' href="view_post.php?delete_post_id&id=<?php echo $post['post_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                                <td><?php echo htmlspecialchars($post['post_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-posts-message">No posts found for this user.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    
</body>
</html>
