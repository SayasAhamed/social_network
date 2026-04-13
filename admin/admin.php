<?php
session_start();
include("../includes/connection.php");

// Check if the admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: signin.php");
    exit();
}

// Display success message if exists in the URL
if (isset($_GET['message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
}

// Whitelist of allowed pages for better security
$allowed_pages = ['users', 'posts', 'comments', 'reports', 'logout'];
$page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) ? $_GET['page'] : 'dashboard';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../images/admin_dash.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #fff;
            margin-top: 50px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .content {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            padding: 2rem;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1.5s ease-in-out;
            color: #f0f0f0;
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

        footer {
            text-align: center;
            font-size: 1rem;
            color: #f0f0f0;
            padding: 15px 0;
            margin-top: 50px;
            background-color: rgba(0, 0, 0, 0.8);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            .content {
                width: 100%;
                padding: 1rem;
            }
        }

    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <!-- Include the Navigation Bar -->
    <?php include 'admin_includes/adminheader.php'; ?>

    <div class="content">
        <?php
        // Load the selected page dynamically
        switch ($page) {
            case 'users':
                include 'admin_users.php';
                break;
            case 'posts':
                include 'admin_posts.php';
                break;
            case 'comments':
                include 'admin_comments.php';
                break;
            case 'reports':
                include 'reports.php';
                break;
            case 'logout':
                include 'admin_logout.php';
                break;
            default:
                echo "<p>Welcome to the admin dashboard. Please select an option from the menu.</p>";
        }
        ?>
    </div>

    <div class="floating-btn">
    <a href="admin.php">Back to Dashboard</a>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Social Hub Network's Admin Dashboard</p>
    </footer>

</body>
</html>
