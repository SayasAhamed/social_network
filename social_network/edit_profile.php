<?php
session_start();
include("includes/header.php");

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "social_network");

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
</head>
<body>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form action="" method="post" enctype="multipart/form-data">
            <table class="table table-bordered table-hover">
                <tr align="center">
                    <td colspan="6" class="active"><h2>Edit Your Profile</h2></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Change Your Firstname</td>
                    <td>
                        <input class="form-control" type="text" name="f_name" required value="<?php echo htmlspecialchars($first_name); ?>">
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Change Your Lastname</td>
                    <td>
                        <input class="form-control" type="text" name="l_name" required value="<?php echo htmlspecialchars($last_name); ?>">
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Change Your Username</td>
                    <td>
                        <input class="form-control" type="text" name="u_name" required value="<?php echo htmlspecialchars($user_name); ?>">
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Description</td>
                    <td>
                        <input class="form-control" type="text" name="describe_user" required value="<?php echo htmlspecialchars($describe_user); ?>">
                    </td>
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
                    <td>
                        <input class="form-control" type="email" name="u_email" required value="<?php echo htmlspecialchars($user_email); ?>">
                    </td>
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
                    <td>
                        <input class="form-control input-md" type="date" name="u_birthday" required value="<?php echo htmlspecialchars($user_birthday); ?>">
                    </td>
                </tr>

                <!-- recover password option-->
                <tr>
                    <td style="font-weight:bold;">Forgotten Password</td>
                    <td>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Turn On</button>

                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="recovery.php?id=<?php echo $user_id; ?>" method="post" id="f">
                                                <strong>What is your School Best Friend Name?</strong>
                                                <textarea class="form-control" cols="83" rows="4" name="content" placeholder="Someone"></textarea><br>
                                                <input type="submit" name="sub" value="submit" style="width:100px;"><br><br>
                                                <pre>Answer the above question we will ask this question if you forgot your <br> password.</pre><br><br>
                                            </form>
                                            <?php
                                            if(isset($_POST['sub'])){
                                                $bfn = htmlentities($_POST['content']);

                                                if($bfn == ""){
                                                    echo "<script>alert('Please fill in the field')</script>";
                                                    echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self')</script>";
                                                    exit();
                                                } else {
                                                    $update = "UPDATE users SET recovery_account ='$bfn' WHERE user_id = '$user_id'";

                                                    $run = mysqli_query($conn, $update);

                                                    if($run){
                                                        echo "<script>alert('Working...')</script>";
                                                        echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self')</script>";
                                                    } else {
                                                        echo "<script>alert('Error while Updating Information!')</script>";
                                                        echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self')</script>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
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
    <div class="col-sm-2"></div>            
</div>
</body>
</html>

<script type="text/javascript">
    function Show_password() {
        var passwordField = document.getElementById("mypass");
        var checkbox = document.getElementById("showPasswordCheckbox");
        if (checkbox.checked) {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

<?php
if(isset($_POST["update"])){
    $f_name = htmlentities($_POST['f_name']); 
    $l_name = htmlentities($_POST['l_name']); 
    $u_name = htmlentities($_POST['u_name']); 
    $describe_user = htmlentities($_POST['describe_user']); 
    $Relationship_status = htmlentities($_POST['Relationship']); // Use correct variable name
    $u_pass = htmlentities($_POST['u_pass']); 
    $u_email = htmlentities($_POST['u_email']); 
    $u_country = htmlentities($_POST['u_country']); 
    $u_gender = htmlentities($_POST['u_gender']); 
    $u_birthday = htmlentities($_POST['u_birthday']); 

    // Correct the SQL query with the proper column names
    $update = "UPDATE users SET f_name = '$f_name', l_name = '$l_name', user_name = '$u_name', describe_user = '$describe_user', Relationship = '$Relationship_status', user_pass = '$u_pass', user_email = '$u_email', user_country = '$u_country', user_gender = '$u_gender', user_birthday = '$u_birthday' WHERE user_id = '$user_id'";

    $run = mysqli_query($conn, $update);

    if($run){
        echo "<script>alert('Your Profile Updated')</script>";
        echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self')</script>";
    } else {
        echo "<script>alert('Error while Updating Information!')</script>";
        echo "<script>window.open('edit_profile.php?u_id=$user_id', '_self')</script>";
    }
}
?>
