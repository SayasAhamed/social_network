<?php
include("../includes/connection.php");

// Check if the user ID is set in the URL
if (isset($_GET['u_id'])) {
    $user_id = intval($_GET['u_id']); // Get the user ID from the URL and ensure it's an integer

    // Start a transaction to ensure all deletions are done together
    mysqli_begin_transaction($conn);

    try {
        // Delete all posts by the user
        $delete_posts = "DELETE FROM posts WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_posts);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        // Delete all comments made by the user
        $delete_comments = "DELETE FROM comments WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_comments);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        // Delete the user from the users table
        $delete_user = "DELETE FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_user);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect to the admin dashboard after deletion
        header("Location: admin.php");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction if there is any error
        mysqli_roll_back($conn);
        echo "<p>Error: Could not delete user. Please try again later.</p>";
    }
} else {
    echo "<p>No user ID specified.</p>";
}
?>
