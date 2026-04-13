<?php

// Fetch search term if any
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .user-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .result-card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            object-fit: cover; 
        }
        .result-card h4, .result-card p {
            margin: 0;
            padding: 5px 0;
        }
        .result-card .btn-link {
            padding-left: 0;
            color: #337ab7;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin.php">Admin Panel</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="admin.php?page=users">Manage Users</a></li>
                <li><a href="admin.php?page=posts">Manage Posts</a></li>
                <li><a href="admin.php?page=comments">Manage Comments</a></li>
                <li><a href="admin.php?page=reports">User Reports</a></li>
                <li><a href="admin_logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a></li>
            </ul>

            <!-- Right-aligned search form -->
            <form method="GET" action="" class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" name="search_term" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <div class="form-group">
                <select name="search_type" class="form-control">
                    <option value="users" <?php echo $search_type === 'users' ? 'selected' : ''; ?>>Users</option>
                    <option value="posts" <?php echo $search_type === 'posts' ? 'selected' : ''; ?>>Posts</option>
                    <option value="user_email" <?php echo $search_type === 'user_email' ? 'selected' : ''; ?>>Specific Email</option>
                    <option value="user_id" <?php echo $search_type === 'user_id' ? 'selected' : ''; ?>>User ID</option>
                </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($search_term): ?>
        <?php if ($search_type === 'users'): ?>
            <!-- Search Users -->
            <?php
            $user_query = "SELECT * FROM users WHERE user_name LIKE '%$search_term%' OR user_email LIKE '%$search_term%'";
            $user_result = mysqli_query($conn, $user_query);

            if (mysqli_num_rows($user_result) > 0) {
                echo "<h3 class='text-primary'>User Search Results</h3>";
                while ($user = mysqli_fetch_assoc($user_result)) {
                    echo "<div class='result-card'>
                            <div class='media'>
                                <img src='../users/{$user['user_image']}' alt='User Image' class='user-image'>
                                <div class='media-body'>
                                    <h4 style='margin-top:10px;'><a href='view_user.php?u_id={$user['user_id']}'>{$user['user_name']}</a></h4>
                                    <p>{$user['user_email']}</p>
                                    <a href='view_user.php?u_id={$user['user_id']}' class='btn btn-info'>View Profile</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>No users found matching '$search_term'.</p>";
            }
        elseif ($search_type === 'posts'): ?>
            <!-- Search Posts -->
            <?php
            $post_query = "SELECT posts.*, users.user_id, users.user_name, users.user_email, users.user_image FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_content LIKE '%$search_term%'";
            $post_result = mysqli_query($conn, $post_query);

            if (mysqli_num_rows($post_result) > 0) {
                echo "<h3 class='text-primary'>Post Search Results</h3>";
                while ($post = mysqli_fetch_assoc($post_result)) {
                    echo "<div class='result-card'>
                            <div class='media'>
                                <img src='../users/{$post['user_image']}' alt='User Image' class='user-image'>
                                <div class='media-body'>
                                    <h4 style='margin-top:10px;'><a href='view_user.php?u_id={$post['user_id']}'>{$post['user_name']}</a></h4>
                                    <p>{$post['post_content']}</p>
                                    <a href='view_post.php?id={$post['post_id']}' class='btn btn-info'>View Post</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>No posts found matching '$search_term'.</p>";
            }
        elseif ($search_type === 'user_email'): ?>
            <!-- Search Specific User by Email -->
            <?php
            $user_query = "SELECT * FROM users WHERE user_email LIKE '%$search_term%'";
            $user_result = mysqli_query($conn, $user_query);

            if (mysqli_num_rows($user_result) > 0) {
                $user = mysqli_fetch_assoc($user_result);
                echo "<h3 class='text-primary'>User Profile</h3>";
                echo "<div class='result-card'>
                        <div class='media'>
                            <img src='../users/{$user['user_image']}' alt='User Image' class='user-image'>
                            <div class='media-body'>
                                <h4 style='margin-top:10px;'>{$user['user_name']}</h4>
                                <p>Email: {$user['user_email']}</p>
                                <a href='view_user.php?u_id={$user['user_id']}' class='btn btn-info'>View Profile</a>
                            </div>
                        </div>
                    </div>";
            } else {
                echo "<p>No user found matching '$search_term'.</p>";
            }
        elseif ($search_type === 'user_id'): ?>
            <!-- Search Specific User by ID -->
            <?php
            $user_query = "SELECT * FROM users WHERE user_id = '$search_term'";
            $user_result = mysqli_query($conn, $user_query);

            if (mysqli_num_rows($user_result) > 0) {
                $user = mysqli_fetch_assoc($user_result);
                echo "<h3 class='text-primary'>User Profile</h3>";
                echo "<div class='result-card'>
                        <div class='media'>
                            <img src='../users/{$user['user_image']}' alt='User Image' class='user-image'>
                            <div class='media-body'>
                                <h4 style='margin-top:10px;'>{$user['user_name']}</h4>
                                <p>Email: {$user['user_email']}</p>
                                <a href='view_user.php?u_id={$user['user_id']}' class='btn btn-info'>View Profile</a>
                            </div>
                        </div>
                    </div>";
            } else {
                echo "<p>No user found with ID '$search_term'.</p>";
            }
        endif; ?>
    <?php endif; ?>

    </div>
</body>
</html>