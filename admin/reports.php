<?php
// Start the session and check if the admin is logged in
include("../includes/connection.php");
// Start output buffering to avoid "headers already sent" issues



// Handle form submissions (Mark as Fixed, Delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'fixed') {
        // Update report status to 'Fixed'
        $query = "UPDATE users SET report_status = 'Fixed' WHERE user_id = '$user_id'";
        mysqli_query($conn, $query);
    } elseif ($action == 'delete') {
        // Delete the report
        $query = "UPDATE users SET report = NULL, report_status = 'Pending' WHERE user_id = '$user_id'";
        mysqli_query($conn, $query);
    }

    // Redirect to reports page in admin dashboard after action
    header("Location: admin.php?page=reports");
    exit();
}

// Fetch all users' reports along with their profile picture and report image
$query = "SELECT user_id, user_email, report, report_status, user_image, report_image FROM users WHERE report IS NOT NULL";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
    <style>
        /* General styles for body and page layout */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 2rem;
            color: #444;
        }
        
        table {
            width: 100%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        table thead {
            background-color: #007bff;
            color: white;
        }
        
        table th, table td {
            padding: 15px;
            text-align: left;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: scale(1.1);
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .status-fixed {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
        }

        .icon-fixed {
            color: green;
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .icon-fixed:hover {
            transform: scale(1.3);
        }

        /* Add styles for profile picture */
        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        /* Ensure text is black for email and report */
        .user-email, .user-report {
            color: black;
        }

        /* Styling for the report image */
        .report-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Styles for modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0); /* Black with opacity */
            background-color: rgba(0,0,0,0.4); /* Black with opacity */
            padding-top: 60px;
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 35px;
            text-decoration: none;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2 style="color:#fff;">Reports</h2>

<table>
    <thead>
        <tr>
            <th>Profile Picture</th>
            <th>User Email</th>
            <th>Report</th>
            <th>Report Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td>
                <?php if (!empty($row['user_image'])) { ?>
                    <img src="../users/<?php echo $row['user_image']; ?>" alt="Profile Picture" class="profile-picture">
                <?php } else { ?>
                    <img src="../users/default-avatar.png" alt="Default Avatar" class="profile-picture">
                <?php } ?>
            </td>
            <td class="user-email"><?php echo $row['user_email']; ?></td>
            <td class="user-report"><?php echo $row['report']; ?></td>
            <td>
                <?php if (!empty($row['report_image'])) { ?>
                    <img src="../report_image/<?php echo $row['report_image']; ?>" alt="Report Image" class="report-image" onclick="openModal('<?php echo '../report_image/' . $row['report_image']; ?>')">
                <?php } else { ?>
                    <p>No image uploaded</p>
                <?php } ?>
            </td>
            <td class="<?php echo ($row['report_status'] == 'Fixed') ? 'status-fixed' : 'status-pending'; ?>">
                <?php echo $row['report_status']; ?>
                <?php if ($row['report_status'] == 'Fixed') { ?>
                    <span class="icon-fixed">✔️</span>
                <?php } ?>
            </td>
            <td>
                <form action="reports.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    <?php if ($row['report_status'] == 'Pending') { ?>
                        <button type="submit" name="action" value="fixed" class="btn btn-success">Mark as Fixed</button>
                    <?php } ?>
                    <button type="submit" name="action" value="delete" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Modal for image viewing -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img id="modalImage" class="modal-content">
</div>

<script>
// Open modal and display the image
function openModal(imageSrc) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = imageSrc;

    // Close modal when clicking outside of the image
    modal.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
}

// Close the modal
function closeModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
}
</script>

</body>
</html>
