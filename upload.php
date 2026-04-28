<?php
    session_start();
    include_once("includes/header.php");

    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        
    
<nav class="navbar navbar-default navbar-custom">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="home.php">Social Hub</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="members.php">Find People</a></li>
                <li><a href="messages.php?u_id=new">Messages</a></li>
            </ul>
        </div>
    </div>
</nav>
    
</body>

    
</html>
