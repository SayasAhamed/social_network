<?php
include("../includes/connection.php");

// Fetch all users from the database
$users_query = "SELECT * FROM users"; // Adjust the column names as per your table structure
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - All Users</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="admin_includes/file.css">
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 2rem;
            color: #333;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
        }

        /* Table container */
        .user-table-container {
            width: 90%;
            margin: 30px auto;  
            padding: 30px;
            background: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
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

        /* Profile images */
        td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 5px;
        }

        .action-buttons form {
            display: inline-block;
        }
        .btn{
            background-color: #2196f3;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            font-size: 11px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover{
            background-color: #1976d2;
            transform: scale(1.1);
        }
        .btn a{
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .action-buttons button, .action-buttons a {
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-buttons button:hover, .action-buttons a:hover {
            transform: scale(1.1);
        }

        .action-buttons button:active, .action-buttons a:active {
            transform: scale(1);
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

    </style>
</head>
<body>

    <h2 style="color:#fff;">Admin Dashboard - All Users</h2>
    <div class="user-table-container">
        <table>
            <tr>
                <th>User ID</th> <!-- Add User ID column -->
                <th>Profile</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td> <!-- Display User ID -->
                    <td><img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" alt="User Profile Image"></td>
                    <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <!-- View Button - Redirects to view_user.php -->
                            <a class='btn-success' href="view_user.php?u_id=<?php echo $user['user_id']; ?>">View</a>

                            <!-- Edit Button - Redirects to edit_post.php (Assuming edit post for user is correct) -->
                            <a class='btn btn-success' href="admin_profile_edit.php?u_id=<?php echo $user['user_id']; ?>">Edit</a>

                            <!-- Delete Button - Redirects to view_user.php for confirmation and deletion -->
                            <a class='btn-danger' href="view_user.php?u_id=<?php echo $user['user_id']; ?>&delete=true" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>

