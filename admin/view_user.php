<?php
include("../includes/connection.php");
include("admin_includes/adminheader.php");

// Check if the user ID is set in the URL
if (isset($_GET['u_id'])) {
    $user_id = intval($_GET['u_id']); // Get the user ID from the URL and ensure it's an integer

    // Prepare the query to fetch user details based on the user ID
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user was found
    if ($user = mysqli_fetch_assoc($result)) {
        // User found, proceed to display profile
    } else {
        // User not found, redirect or show an error
        echo "<p>User not found.</p>";
        exit;
    }
} else {
    echo "<p>No user ID specified.</p>";
    exit;
}

// Handle the deletion of the user if the 'delete' parameter is present
if (isset($_GET['delete']) && $_GET['delete'] == 'true') {
    // Fetch user details to get the image path
    $user_image = $user['user_image'];  // Store the image name

    // Delete user image from the server
    $image_path = "../users/" . $user_image;
    if (file_exists($image_path) && is_file($image_path)) {
        unlink($image_path); // Delete the image file
    }

    // Prepare the delete query
    $delete_query = "DELETE FROM users WHERE user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $user_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        // Redirect after successful deletion
        header("Location: admin.php?page=users");
        exit;
    } else {
        echo "<p>Error deleting user.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>View User Profile - <?php echo htmlspecialchars($user['user_name']); ?></title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Profile container */
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        /* Profile picture */
        .profile-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        /* Username and email */
        .profile-container h2 {
            font-size: 1.8rem;
            color: #333;
        }

        .profile-container p {
            font-size: 1rem;
            color: #666;
        }

        /* Bio section */
        .profile-container .bio {
            margin-top: 20px;
            font-style: italic;
            color: #333;
        }

        /* Joined date */
        .profile-container .joined-date {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #888;
        }

        /* Action buttons */
        .action-buttons {
            margin-top: 20px;
        }

        .action-buttons a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 10px;
           
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

        footer {
            text-align: center;
            font-size: 1rem;
            color: #f0f0f0;
            padding: 15px 0;
            margin-top: 50px;
            background-color: rgba(0, 0, 0, 0.8);
        }

    </style>
</head>
<body>

<div class="profile-container">
    <!-- Display Profile Picture -->
    <img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="Profile Picture">

    <!-- Display Username -->
    <h2><?php echo htmlspecialchars($user['user_name']); ?></h2>

    <!-- Display Email -->
    <p>Email: <?php echo htmlspecialchars($user['user_email']); ?></p>

    <!-- Display Bio if available -->
    <?php if (!empty($user['describe_user'])): ?>
        <div class="bio">
            <p><?php echo htmlspecialchars($user['describe_user']); ?></p>
        </div>
    <?php endif; ?>

    <!-- Display Account Creation Date -->
    <div class="joined-date">
        <p>Joined: <?php echo date("F j, Y", strtotime($user['user_reg_date'])); ?></p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a class='btn-success' href="view_profile.php?u_id=<?php echo $user['user_id']; ?>">Profile</a>
        <a class='btn-info ' href="admin_profile_edit.php?u_id=<?php echo $user['user_id']; ?>">Edit User</a>
        <a class='btn-danger' href="view_user.php?u_id=<?php echo $user['user_id']; ?>&delete=true" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</a>
    </div>
</div>

<div style="margin-top: 10px; text-align: center; margin-bottom: 0px;">
    <a class="btn btn-success" href="admin.php"><p>Back to Dashboard</p></a>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Social Hub Network's Admin Dashboard</p>
</footer>

</body>
</html>
