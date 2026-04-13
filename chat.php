<?php
session_start();

include('includes/connection.php');

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}


$username = $_SESSION['user_email'];
$selectedUser = '';

// Fetch user_name from the database based on session username
$sql_user_name = "SELECT user_name FROM users WHERE user_email = '$username'";
$result_user_name = $conn->query($sql_user_name);
if ($result_user_name->num_rows > 0) {
    $row_user_name = $result_user_name->fetch_assoc();
    $user_name = $row_user_name['user_name']; // Now you have user_name as a variable
}

if (isset($_GET['user'])) {
    $selectedUser = $_GET['user'];
    $selectedUser    = mysqli_real_escape_string($conn, $selectedUser);
    $showChatBox = true; // Set to true only when a user is selected
} else {
    $showChatBox = false; // Set to false initially
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Chat</title>
    
<style>
    /* Background Animation */
    @keyframes backgroundMove {
        0% { background-position: 0 0; }
        50% { background-position: 100% 100%; }
        100% { background-position: 0 0; }
    }   
    body {
        background: url('images/Background2.png') no-repeat center center fixed;
        background-size: cover;
        font-family: Arial, sans-serif;
        animation: backgroundMove 30s linear infinite;
        margin-top: 50px; /* Add top margin to prevent overlap with the navbar */
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden; /* Hide vertical scrollbar */
        position: relative;
        animation: fadeInUp 1s ease-in-out;
    }
    .header1 {
        background-color: #0084ff;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header1 h1 {
        margin: 0;
    }
    .logout {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #0056b3;
        transition: background-color 0.3s;
    }
    .logout:hover {
        background-color: #004080;
    }
    .chat-box {
        display: block;
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        height: 400px;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .chat-box-header {
        background-color: #0084ff;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-box-header h2 {
        margin: 0;
    }
    .close-btn {
        color: #fff;
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 20px;
    }
    .close-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
    }
    .chat-box-body {
        padding: 20px;
        overflow-y: auto;
        /* height: 300px; */
        height: 65%;
    }
    .message {
        background-color: #f2f2f2;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        max-width: 80%;
        word-wrap: break-word;
    }
    .message p {
        margin: 5px 0;
    }
    .chat-form {
        padding: 10px;
        border-top: 1px solid #ccc;
        background-color: #f9f9f9;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        position: absolute;
        bottom: 0;
        width: calc(100% - 20px);
        left: 0px;
    }
    .chat-form input[type="text"] {
        /* width: calc(100% - 70px); */
        padding: 10px;
        margin-right: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .chat-form button {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .chat-form button:hover {
        background-color: #0056b3;
    }

    /* Responsive adjustments */
    @media only screen and (max-width: 600px) {
        .container {
            max-width: 100%;
            border-radius: 0;
        }
        .header {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .chat-box {
            width: calc(100% - 40px);
            left: 20px;
            right: auto;
        }
        .chat-form {
            width: calc(100% - 20px);
            left: 10px;
        }
    }

    .chat-box-body::-webkit-scrollbar {
        display: none;
    }

    .account-info {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .welcome h2 {
        margin: 0;
        color: #333;
    }

    .user-list h2 {
        margin-top: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .user-list ul {
        list-style-type: none;
        padding: 10px;
        margin: 0;
    }

    .user-list ul li {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 8px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

    .user-list ul li:hover {
        transform: translateY(-3px);
    }

    .user-list ul li a {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 10px;
        color: #333;
        width: 100%;
    }

    .user-list ul li img.profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .user-list ul li a:hover {
        background-color: #f0f0f0;
        border-radius: 8px;
    }


    nav.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    text-decoration: none;
    color: #fff;
    padding: 12px;
    z-index: 1000; /* Ensure navbar stays on top of other elements */
    }

    nav.navbar a {
        color: white;
        text-decoration: none;
        padding: 10px;
        margin-right: 20px;
        font-size: 13px;
    }

    nav.navbar a:hover {
        transform: translateY(-3px);
    }

    .profile-image {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

</style>
</head>
<body>
<?php
    $user = $_SESSION['user_email'];
    $get_user = "SELECT * FROM users WHERE user_email='$user'"; 
    $run_user = mysqli_query($conn, $get_user);
    $row = mysqli_fetch_array($run_user);
    
    $user_id = $row['user_id']; 
    $first_name = $row['f_name'];
    $user_name = $row['user_name'];
    $user_image = $row['user_image'];
?>

<nav class="navbar" style="text-decoration: none;">
    
       <a href="includes/contact.php">Social Hub</a>
       <a href='profile.php?u_id=<?php echo $user_id; ?>'><?php echo htmlspecialchars($first_name); ?></a>
       <a href='home.php?u_id=<?php echo $user_id; ?>'>Home</a>
       <a href="members.php">Find People</a>
       <a href='my_post.php?u_id=<?php echo $user_id; ?>'>My Posts</a>
           
</nav>


<div class="container">
    <div class="header1">
        <img src="users/<?php echo $user_image; ?>" alt="<?php echo htmlspecialchars($user); ?>'s Profile Picture" class="profile-image">
        <h1>My Account</h1>
        <a href="home.php" class="back-to-menu">Profile</a>
        
    </div>
    <div class="account-info">
        <div class="welcome">
            <h2>Welcome, <?php echo ucfirst($user_name); ?>!</h2>
        </div>
        <div class="user-list">
            <h2>Select a User to Chat With:</h2>
            <ul>
                <?php 
                // Fetch all users except the current user
                $sql = "SELECT user_name, user_image FROM users WHERE user_name != '$user_name'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user = $row['user_name'];
                        $user_image = $row['user_image']; // Get the image of the current user in the loop
                        $user = ucfirst($user); // Capitalize the first letter of the username
                        echo "<li><img src='users/$user_image' alt='$user's Profile Picture' class='profile-pic'> <a href='chat.php?user=$user'>$user</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <?php if ($showChatBox): ?>
    <div class="chat-box" id="chat-box">
        <div class="chat-box-header">
            <h2><?php echo ucfirst($selectedUser); ?></h2>
            <button class="close-btn" onclick="closeChat()">✖</button>
        </div>
        <div class="chat-box-body" id="chat-box-body">
            <!-- Chat messages will be loaded here -->
        </div>
        <form class="chat-form" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $user_name; ?>">
            <input type="hidden" id="receiver" value="<?php echo $selectedUser; ?>">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>
<?php endif; ?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    function closeChat() {
        document.getElementById("chat-box").style.display = "none";
    }


    // Function to toggle chat box visibility
    function toggleChatBox() {
    var chatBox = document.getElementById("chat-box");
    if (chatBox.style.display === "none") {
        chatBox.style.display = "block"; // Show the chat box
    } else {
        chatBox.style.display = "none"; // Hide the chat box
    }
}


function fetchMessages() {
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            
            $.ajax({
                url: 'fetch_messages.php',
                type: 'POST',
                data: {sender: sender, receiver: receiver},
                success: function(data) {
                    $('#chat-box-body').html(data);
                    scrollChatToBottom();
                }
            });
        }


        // Function to scroll the chat box to the bottom
        function scrollChatToBottom() {
            var chatBox = $('#chat-box-body');
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }

 
        
        $(document).ready(function() {
            // Fetch messages every 3 seconds
            
            fetchMessages();
            setInterval(fetchMessages, 3000);
        });


            // Submit the chat message
            $('#chat-form').submit(function(e) {
            e.preventDefault();
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            var message = $('#message').val();

            $.ajax({
                url: 'submit_message.php',
                type: 'POST',
                data: {sender: sender, receiver: receiver, message: message},
                success: function() {
                    $('#message').val('');
                    fetchMessages(); // Fetch messages after submitting
                }
            });

            });


</script>
    
</body>
</html>