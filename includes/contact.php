<?php
session_start();
include("connection.php");

// Ensure the user is logged in, else redirect to login page
if (!isset($_SESSION['user_email'])) {
    header("Location: ../index.php");
    exit();
}

$user_email = $_SESSION['user_email']; // Get logged-in user's email

// Fetch the user's ID
$get_user_id_query = "SELECT user_id FROM users WHERE user_email = '$user_email'";
$result = mysqli_query($conn, $get_user_id_query);
$user_row = mysqli_fetch_assoc($result);
$user_id = $user_row['user_id']; // Assign the user_id

// Flag to track success message
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the report message from the form
    $report_message = mysqli_real_escape_string($conn, $_POST['report_message']);
    $submitted_time = date('Y-m-d H:i:s'); // Get current timestamp

    // Handle the image upload
    $target_dir = "../report_image/"; // Folder to store the uploaded image
    $target_file = $target_dir . basename($_FILES["report_image"]["name"]);
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $upload_ok = 1;
    $error_message = '';

    // Check if the image is a valid image file and is uploaded
    if (isset($_POST["submit"])) {
        // If the report message is empty and there's no image, show error
        if (empty($report_message) && empty($_FILES["report_image"]["tmp_name"])) {
            $error_message = "Please enter a description or upload an image.";
            $upload_ok = 0;
        }

        // If there's an image uploaded
        if (!empty($_FILES["report_image"]["tmp_name"])) {
            $check = getimagesize($_FILES["report_image"]["tmp_name"]);
            if ($check !== false) {
                $upload_ok = 1;
            } else {
                $error_message = "File is not an image.";
                $upload_ok = 0;
            }

            // Check file size (limit to 5MB)
            if ($_FILES["report_image"]["size"] > 5000000) {
                $error_message = "Sorry, your file is too large.";
                $upload_ok = 0;
            }

            // Allow certain file formats
            if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png" && $image_file_type != "gif") {
                $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $upload_ok = 0;
            }

            // If image validation failed, stop the upload
            if ($upload_ok == 0) {
                $error_message = "Sorry, your file was not uploaded.";
            }
        }

        if ($upload_ok == 1) {
            // Delete the previous image (if any) from the folder and database
            $get_previous_image_query = "SELECT report_image FROM users WHERE user_email = '$user_email'";
            $prev_result = mysqli_query($conn, $get_previous_image_query);
            $prev_row = mysqli_fetch_assoc($prev_result);
            $previous_image = $prev_row['report_image'];

            // If the user had uploaded a previous image, delete it from the folder
            if (!empty($previous_image) && file_exists($previous_image)) {
                unlink($previous_image); // Delete image from the folder
            }

            // Upload the new image if there is one
            if (!empty($_FILES["report_image"]["tmp_name"])) {
                if (move_uploaded_file($_FILES["report_image"]["tmp_name"], $target_file)) {
                    // Insert the report and image into the database
                    $query = "UPDATE users SET report = '$report_message', report_status = 'Pending', submitted_time = '$submitted_time', report_image = '$target_file' WHERE user_email = '$user_email'";
                }
            } else {
                // If no image is uploaded, just update the text report
                $query = "UPDATE users SET report = '$report_message', report_status = 'Pending', submitted_time = '$submitted_time', report_image = NULL WHERE user_email = '$user_email'";
            }

            if (mysqli_query($conn, $query)) {
                // Set a flag for the success message and redirect
                $_SESSION['success_message'] = "Your issue has been submitted successfully!";
                header("Location: contact.php?submitted=true"); // Redirect to prevent form resubmission
                exit();
            } else {
                $error_message = "There was an error submitting your issue. Please try again.";
            }
        }
    }
}

// Fetch the user's current report, status, and submitted time
$query = "SELECT report, report_status, submitted_time, report_image FROM users WHERE user_email = '$user_email'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Social Hub</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
    <style>

        /* Fade-in and Slide-in animations */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            0% { opacity: 0; transform: translateX(-100px); }
            100% { opacity: 1; transform: translateX(0); }
        }
            /* Background Animation */
            @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }
        /* Apply animations to elements */
        body {
            font-family: 'Arial', sans-serif;
            background: url('../images/Background2.png') no-repeat center center fixed;
            background-size: cover;
            animation: backgroundMove 30s linear infinite;
            color: #333;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        h2 {
            font-size: 2rem;
            color: #0056b3;
            margin-bottom: 20px;
            margin-top: 5px;
            text-align: center;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 1s ease-in-out;
        }

        .form-container label {
            font-size: 1rem;
            color: #333;
            margin-bottom: 10px;
            text-align: left;
            width: 100%;
        }

        .form-container textarea {
            width: 100%;
            max-width: 570px;
            resize: none;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            resize: vertical;
            margin-bottom: 20px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-container textarea:focus {
            border-color: #0056b3;
        }

        .form-container input[type="file"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 1rem;
            margin-bottom: 20px;
            width: 100%;
        }

        .form-container .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 570px;
        }

        .form-container .btn:hover {
            background-color: #0056b3;
        }

        .status-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            width: 100%;
            max-width: 570px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-container {
            margin-top: -20px;
            text-align: center;
            width: 100%;
            max-width: 570px;
        }

        .status-container h3 {
            font-size: 1.5rem;
            color: #333;
        }

        .status-container p {
            font-size: 1.1rem;
            color: #555;
        }

        .status-container .status {
            font-weight: bold;
        }

        .image-preview {
            margin-top: 15px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
        }

        .back-home-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 30px;
            width: 100%;
            max-width: 570px;
            transition: background-color 0.3s ease;
        }

        .back-home-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Contact Us</h2>
    <?php if (isset($error_message)): ?>
        <div class="status-message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label for="report_message">Describe Your Issue</label>
        <textarea name="report_message" id="report_message" rows="5" placeholder="Please provide details"></textarea>

        <label for="report_image">Upload Image (Optional)</label>
        <input type="file" name="report_image" id="report_image" accept="image/*" onchange="previewImage()">

        <div class="image-preview" id="imagePreview">
            <!-- Image preview will be displayed here -->
        </div>

        <button type="submit" name="submit" class="btn">Submit</button>
    </form>

    <!-- Display success message if issue was submitted -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="status-message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <div class="status-container">
        <h3>Your Current Report Status</h3>
        <p class="status"><?php echo !empty($user_data['report_status']) ? $user_data['report_status'] : 'No Report Submitted'; ?></p>
        <?php if (!empty($user_data['report'])): ?>
            <p><?php echo $user_data['report']; ?></p>
        <?php endif; ?>

        <!-- Display image preview -->
        <?php if (!empty($user_data['report_image'])): ?>
            <div class="image-preview">
                <img src="<?php echo $user_data['report_image']; ?>" alt="Report Image">
            </div>
        <?php endif; ?>
    </div>

    <form action="../profile.php" method="get">
        <button type="submit" name="u_id" value="<?php echo $user_id; ?>" class="back-home-btn">Return to Profile</button>
    </form>
</div>

<script>
    // Function to preview the image after selecting
    function previewImage() {
        const file = document.getElementById('report_image').files[0];
        const preview = document.getElementById('imagePreview');

        // Clear previous preview
        preview.innerHTML = "";

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = "100%";
                img.style.height = "auto";
                img.style.border = "1px solid #ddd";
                img.style.borderRadius = "8px";
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
