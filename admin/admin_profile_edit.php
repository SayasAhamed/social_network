<?php

// Include connection and header files
include("../includes/connection.php");
include("admin_includes/adminheader.php");

// Check if the user ID is set in the URL
if (isset($_GET['u_id'])) {
    $user_id = intval($_GET['u_id']); // Ensure the user ID is an integer

    // Fetch user data based on the user ID
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user was found
    if ($user = mysqli_fetch_assoc($result)) {
        // Update user information on form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize user inputs
            $user_name = mysqli_real_escape_string($conn, trim($_POST['user_name']));
            $user_email = mysqli_real_escape_string($conn, trim($_POST['user_email']));

            // Handle password input (if provided)
            $user_pass = !empty($_POST['user_pass']) ? mysqli_real_escape_string($conn, trim($_POST['user_pass'])) : null;

            // If password is not provided, do not include it in the query
            if (empty($user_pass)) {
                // Update query without password
                $update_query = "UPDATE users SET user_name = ?, user_email = ? WHERE user_id = ?";
                $update_stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($update_stmt, "ssi", $user_name, $user_email, $user_id);
            } else {
                // If a new password is provided, include it in the query
                $update_query = "UPDATE users SET user_name = ?, user_email = ?, user_pass = ? WHERE user_id = ?";
                $update_stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($update_stmt, "sssi", $user_name, $user_email, $user_pass, $user_id);
            }

            // Execute the update query
            if (mysqli_stmt_execute($update_stmt)) {
                header("Location: view_user.php?u_id=$user_id&message=Profile updated successfully");
                exit;
            } else {
                echo "<p style='color:red;'>Error updating profile. Please try again.</p>";
            }
        }

        // Handle user deletion
        if (isset($_POST['delete_user'])) {
            // Delete the user from the database
            $delete_query = "DELETE FROM users WHERE user_id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, "i", $user_id);

            if (mysqli_stmt_execute($delete_stmt)) {
                header("Location: users_list.php?message=User deleted successfully");
                exit;
            } else {
                echo "<p style='color:red;'>Error deleting user. Please try again.</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
        exit;
    }
} else {
    echo "<p style='color:red;'>No user ID specified.</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        /* Center container */
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Profile picture */
        .profile-pic {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2196f3;
        }

        /* Form labels and inputs */
        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
        }

        /* Password wrapper */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            flex: 1;
        }
        .password-wrapper i {
            margin-top:10px;
            position: absolute;
            right: 10px;
            cursor: pointer;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            width: 100px;
        }
        .btn-primary {
            background-color: #2196f3;
            color: white;
            border: none;
        }
        .btn-secondary {
            background-color: #ff4340;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="Profile Picture" class="profile-pic">
    <h2>Edit Profile for <?php echo htmlspecialchars($user['user_name']); ?></h2><br><br>
    
    <form id="editForm" action="" method="post" onsubmit="return showSaveAlert()">
        <div class="form-group">
            <label for="user_name">Username:</label>
            <input type="text" class="form-control" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_email">Email:</label>
            <input type="email" class="form-control" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_pass">New Password:</label>
            <div class="password-wrapper">
                <input type="password" class="form-control" name="user_pass" placeholder="Enter new password (leave blank to keep old)">
                <i class="glyphicon glyphicon-eye-close" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" onclick="showCancelAlert()" class="btn btn-secondary">Cancel</button>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        var passwordField = document.querySelector('[name="user_pass"]');
        var toggleIcon = document.getElementById('togglePassword');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('glyphicon-eye-close');
            toggleIcon.classList.add('glyphicon-eye-open');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('glyphicon-eye-open');
            toggleIcon.classList.add('glyphicon-eye-close');
        }
    }

    // Show an alert when the "Save" button is clicked
    function showSaveAlert() {
        alert("Changes have been saved successfully!");
        return true;  // This allows the form to be submitted
    }

    // Show an alert when the "Cancel" button is clicked
    function showCancelAlert() {
        alert("Changes have been canceled.");
        window.location.href = 'view_user.php?u_id=<?php echo $user_id; ?>'; // Redirect to the view page after cancel
    }
</script>

</body>
</html>


