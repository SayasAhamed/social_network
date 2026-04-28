<?php
session_start();
include("includes/header.php");
include("includes/connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

// Get user information
$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($conn, $get_user);
$row = mysqli_fetch_array($run_user);

$user_name = $row['user_name'];
$user_image = $row['user_image']; // Assuming this field is in your database

// Additional user details
$first_name = $row['f_name'];
$last_name = $row['l_name'];
$describe_user = $row['describe_user'];
$Relationship_status = $row['Relationship'];
$user_pass = $row['user_pass'];
$user_email = $row['user_email'];
$user_country = $row['user_country'];
$user_gender = $row['user_gender'];
$user_birthday = $row['user_birthday'];
$user_id = $row['user_id']; // Assuming user_id is available
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Account Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
    <style>
            /* Background Animation */
            @keyframes backgroundMove {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

        body {
            background: url('images/edit_info.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: backgroundMove 30s linear infinite;
        }

        /* Fade-in and Slide-in animations */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            0% { opacity: 0; transform: translateX(-100px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        
        .table {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            
        }
        .btn-info {
            background-color: #3897f0;
            border: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-info:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .p6 {
            animation: fadeInUp 1s ease-in-out;
        }

    </style>
</head>
<body>
<div class="row p6">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form action="" method="post" enctype="multipart/form-data">
            <table class="table table-bordered table-hover">
                <tr align="center">
                    <td colspan="6" class="active"><h2>Edit Your Profile</h2></td>
                </tr>
                <!-- Profile Update Fields -->
                <tr>
                    <td style="font-weight:bold;">Change Your Firstname</td>
                    <td><input class="form-control" type="text" name="f_name" required value="<?php echo htmlspecialchars($first_name); ?>"></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Change Your Lastname</td>
                    <td><input class="form-control" type="text" name="l_name" required value="<?php echo htmlspecialchars($last_name); ?>"></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Change Your Username</td>
                    <td><input class="form-control" type="text" name="u_name" required value="<?php echo htmlspecialchars($user_name); ?>"></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Description</td>
                    <td><input class="form-control" type="text" name="describe_user" required value="<?php echo htmlspecialchars($describe_user); ?>"></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Relationship Status</td>
                    <td>
                        <select class="form-control" name="Relationship">
                            <option><?php echo htmlspecialchars($Relationship_status); ?></option>
                            <option>Engaged</option>
                            <option>Married</option>
                            <option>Single</option>
                            <option>In a Relationship</option>
                            <option>It's Complicated</option>
                            <option>Separated</option>
                            <option>Divorced</option>
                            <option>Widowed</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Password</td>
                    <td>
                        <input class="form-control" type="password" name="u_pass" id="mypass" required value="<?php echo htmlspecialchars($user_pass); ?>">
                        <input type="checkbox" id="showPasswordCheckbox" onclick="Show_password()"><strong>Show Password</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Email</td>
                    <td><input class="form-control" type="email" name="u_email" required value="<?php echo htmlspecialchars($user_email); ?>"></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Country</td>
                    <td>
                        <select class="form-control" name="u_country">
                            <option><?php echo htmlspecialchars($user_country); ?></option>
                            <option>Sri Lanka</option>
                            <option>India</option>
                            <option>USA</option>
                            <option>UK</option>
                            <option>Japan</option>
                            <option>Saudi Arabia</option>
                            <option>France</option>
                            <option>Germany</option>
                            <option>Italy</option>
                            <option>China</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Gender</td>
                    <td>
                        <select class="form-control" name="u_gender">
                            <option><?php echo htmlspecialchars($user_gender); ?></option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Birthdate</td>
                    <td><input class="form-control input-md" type="date" name="u_birthday" required value="<?php echo htmlspecialchars($user_birthday); ?>"></td>
                </tr>

                <!-- Recover password option-->
                <tr>
                    <td style="font-weight:bold;">Forgotten Password</td>
                    <td>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Turn On</button>
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Security Questions</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="post">
                                            <strong>What is your School Best Friend's Name?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="best_friend" placeholder="Someone"></textarea><br>
                                            <strong>What is your Home Town?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="home_town" placeholder="Somewhere"></textarea><br>
                                            <strong>What is your Pet's Name?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="pet_name" placeholder="Pet name"></textarea><br>
                                            <strong>What is your Favorite Color?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="favorite_color" placeholder="Color"></textarea><br>
                                            <strong>What is your Favorite Food?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="favorite_food" placeholder="Food"></textarea><br>
                                            <strong>What is your Favorite Movie?</strong>
                                            <textarea class="form-control" cols="40" rows="1" name="favorite_movie" placeholder="Movie"></textarea><br>
                                            <input type="submit" name="sub" value="Submit" style="width:100px;">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr align="center">
                    <td colspan="6">
                        <input type="submit" class="btn btn-info" name="update" style="width:250px;" value="Update">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="col-md-2"></div>
</div>
</body>
</html>

<script>
function Show_password() {
    var pass = document.getElementById("mypass");
    var checkbox = document.getElementById("showPasswordCheckbox");
    if (checkbox.checked == true) {
        pass.type = "text";
    } else {
        pass.type = "password";
    }
}
</script>

<?php
// Handle the form submission and password update
if (isset($_POST['update'])) {
    $f_name = mysqli_real_escape_string($conn, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($conn, $_POST['l_name']);
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $describe_user = mysqli_real_escape_string($conn, $_POST['describe_user']);
    $Relationship = mysqli_real_escape_string($conn, $_POST['Relationship']);
    $u_pass = mysqli_real_escape_string($conn, $_POST['u_pass']);
    $u_email = mysqli_real_escape_string($conn, $_POST['u_email']);
    $u_country = mysqli_real_escape_string($conn, $_POST['u_country']);
    $u_gender = mysqli_real_escape_string($conn, $_POST['u_gender']);
    $u_birthday = mysqli_real_escape_string($conn, $_POST['u_birthday']);

    // Update the user profile
    $update_user = "UPDATE users SET f_name='$f_name', l_name='$l_name', user_name='$u_name', describe_user='$describe_user', Relationship='$Relationship', user_pass='$u_pass', user_email='$u_email', user_country='$u_country', user_gender='$u_gender', user_birthday='$u_birthday' WHERE user_id='$user_id'";
    $run_update = mysqli_query($conn, $update_user);

    if ($run_update) {
        echo "<script>alert('Your profile has been updated!');</script>";
        echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self');</script>";
    } else {
        echo "<script>alert('Error updating your profile!');</script>";
    }
}
?>

