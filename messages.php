<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");
include("includes/connection.php"); // Ensure you include your database connection

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}
?>
<html>
<head>
    <title>Messages</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>

<style>
    #scroll_messages {
        max-height: 500px;
        overflow: scroll; 
    }
    #btn-msg {
        width: 20%;
        height: 28px;
        border-radius: 5px;
        margin: 5px;
        border: none;
        color: #fff;
        float: right;
        background-color: #2ecc71;
    }
    #select_user {
        max-height: 500px;
        overflow: scroll;
    }
    #green {
        background-color: #2ecc71;
        border-color: #27ae60;
        width: 45%;
        padding: 2.5px;
        font-size: 16px;
        border-radius: 3px;
        float: left;
        margin-bottom: 5px;
    }
    #blue {
        background-color: #3498db;
        border-color: #2980b9;
        width: 45%;
        padding: 2.5px;
        font-size: 16px;
        border-radius: 3px;
        float: right;
        margin-bottom: 5px;
    }
</style>

<body>
<div class="row">
    <?php
    $user_to_msg = $user_from_msg = $user_to_name = '';

    // Check if 'u_id' is set and is a valid number
    if (isset($_GET['u_id']) && is_numeric($_GET['u_id'])) {
        $get_id = intval($_GET['u_id']); // Convert to integer for safety

        $get_user = "SELECT * FROM users WHERE user_id = '$get_id'";
        $run_user = mysqli_query($conn, $get_user);

        if ($run_user && mysqli_num_rows($run_user) > 0) {
            $row_user = mysqli_fetch_array($run_user);
            $user_to_msg = $row_user['user_id'];
            $user_to_name = $row_user['user_name'];
        } else {
            echo "<p>Invalid user ID. User not found.</p>";
        }
    }

    // Retrieve the logged-in user's details
    $user = $_SESSION['user_email'];
    $get_user = "SELECT * FROM users WHERE user_email = '$user'";
    $run_user = mysqli_query($conn, $get_user);

    if ($run_user && mysqli_num_rows($run_user) > 0) {
        $row = mysqli_fetch_array($run_user);
        $user_from_msg = $row['user_id'];
        $user_from_name = $row['user_name'];
    } else {
        echo "<p>Unable to retrieve your details. Please log in again.</p>";
    }
    ?>

    <div class="col-sm-3" id="select_user">
        <?php
        $user_query = "SELECT * FROM users";
        $run_user = mysqli_query($conn, $user_query);

        if ($run_user && mysqli_num_rows($run_user) > 0) {
            while ($row_user = mysqli_fetch_array($run_user)) {
                $user_id = $row_user['user_id'];
                $user_name = $row_user['user_name'];
                $first_name = $row_user['f_name'];
                $last_name = $row_user['l_name'];
                $user_image = $row_user['user_image'];

                echo "
                    <div class='container-fluid'>
                        <a style='text-decoration:none; cursor:pointer; color:#3897f0;' href='messages.php?u_id=$user_id'>
                            <img class='img-circle' src='users/$user_image' width='90px' height='80px' title='$user_name'>
                            <strong>&nbsp $first_name $last_name</strong><br><br>
                        </a>
                    </div>
                ";
            }
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>
    <div class="col-sm-6">
        <div class="load_msg" id="scroll_messages">
            <?php
            if (!empty($user_to_msg) && !empty($user_from_msg)) {
                $sel_msg = "SELECT * FROM user_messages WHERE (user_to ='$user_to_msg' AND user_from ='$user_from_msg') OR (user_from ='$user_to_msg' AND user_to ='$user_from_msg') ORDER BY date ASC";
                $run_msg = mysqli_query($conn, $sel_msg);

                if ($run_msg && mysqli_num_rows($run_msg) > 0) {
                    while ($row_msg = mysqli_fetch_array($run_msg)) {
                        $user_to = $row_msg['user_to'];
                        $user_from = $row_msg['user_from'];
                        $msg_body = $row_msg['msg_body'];
                        $msg_date = $row_msg['date'];
                        ?>

                        <div id="loaded_msg">
                            <p>
                                <?php
                                if ($user_to == $user_to_msg && $user_from == $user_from_msg) {
                                    echo "<div class='message' id='blue' data-toggle='tooltip' title='$msg_date'>$msg_body</div><br><br><br>";
                                } elseif ($user_from == $user_to_msg && $user_to == $user_from_msg) {
                                    echo "<div class='message' id='green' data-toggle='tooltip' title='$msg_date'>$msg_body</div><br><br><br>";
                                }
                                ?>
                            </p>
                        </div>

                        <?php
                    }
                } else {
                    echo "<p>No messages found.</p>";
                }
            } else {
                echo "<p>No messages to display.</p>";
            }
            ?>
        </div>
        <?php
        if (isset($_GET['u_id'])) {
            $u_id = $_GET['u_id'];
            if ($u_id == "new") {
                echo '
                    <form>
                        <center><h3>Select Someone To Start Conversation</h3></center>
                        <textarea class="form-control" placeholder="Enter Your Message" disabled></textarea>
                        <input type="submit" class="btn btn-default" disabled value="Send">
                    </form><br><br>
                ';
            }
            else {
                echo '
                <form action="" method="post">
                    <textarea class="form-control" placeholder="Enter Your Message" name="msg_box" id="message_textarea"></textarea>
                    <input type="submit" name="send_msg" id="btn-msg" value="Send">
                </form><br><br>
                ';
            }
        }
        ?>

        <?php
            if(isset($_POST['send_msg'])){
                $msg = htmlentities($_POST['msg_box']);
                
                if($msg == ""){
                    echo "<h4 style='color:red; text-align:center;'>Message was unable to send!</h4>";
                }
                elseif(strlen($msg) > 37){ 
                    echo "<h4 style='color:red; text-align:center;'>Message is too long! Use only 37 Characters</h4>";
                
                }
                else{
                    $insert = "INSERT INTO user_messages (user_to, user_from, msg_body, date, msg_seen) VALUES ('$user_to_msg', '$user_from_msg', '$msg', NOW(), 'no')";

                    $run_insert = mysqli_query($conn, $insert);
                }
            }
        ?>
    </div> 
    <div class="col-sm-3">
        <?php
            if (isset($_GET['u_id']) && is_numeric($_GET['u_id'])) {
                $get_id = intval($_GET['u_id']);

                $get_user = "SELECT * FROM users WHERE user_id = '$get_id'";
                $run_user = mysqli_query($conn, $get_user);

                if ($run_user && mysqli_num_rows($run_user) > 0) {
                    $row = mysqli_fetch_array($run_user);

                    $user_id = $row['user_id'];
                    $user_name = $row['user_name'];
                    $f_name = $row['f_name'];
                    $l_name = $row['l_name'];
                    $describe_user = $row['describe_user'];
                    $user_country = $row['user_country'];
                    $user_image = $row['user_image'];
                    $register_date = $row['user_reg_date'];
                    $gender = $row['user_gender'];

                    echo"
                        <div class='row'>
                            <div class='col-sm-2'>
                            </div>
                            <center>
                            <div style ='background-color: #e6e6e6;' class='col-sm-9'>
                                <h2>Information About</h2>
                                <img class='img-circle' src='users/$user_image' width='150' height='150'>
                                <br><br>
                                <ul class='list-group'>
                                    <li class='list-group-item' title='Username'><strong>$f_name $l_name</strong></li>
                                    <li class='list-group-item' title='User status'><strong style='color:gray;'>$describe_user</strong></li>
                                    <li class='list-group-item' title='Gender'>$gender</li>
                                    <li class='list-group-item' title='Country'>$user_country</li>
                                    <li class='list-group-item' title='User Registration Date'>$register_date</li>
                                </ul>
                            </div>
                            <div class='col-sm-1'>
                            </div>
                        </div>
                    ";
                } else {
                    echo "<p>User not found.</p>";
                }
            } else {
                echo "<p>Invalid user ID.</p>";
            }
        ?>
    </div>   
</div>
<script>
    var div = document.getElementById("scroll_messages");
    div.scrollTop = div.scrollHeight;
</script>
</body>
</html>
