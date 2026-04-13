<?php
include("includes/connection.php");
include("functions/functions.php");

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}
?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Bootstrap JS (necessary for Bootstrap's dropdown functionality) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- Add this script to enable dropdown toggle and navbar collapse functionality -->
<script>
    $(document).ready(function () {
        // Ensure the navbar collapses correctly on mobile
        $('.navbar-toggle').click(function () {
            $('#navbar-collapse').toggleClass('in'); // This will handle the collapsing of the menu
        });

        // Optionally: ensure dropdown toggle works when clicking on the More menu
        $('.dropdown-toggle').click(function (e) {
            e.preventDefault();
            $(this).next('.dropdown-menu').toggleClass('show');
        });
    });
</script>

<nav class="navbar navbar-default navbar-custom navbar-expand-lg">
    <div class="container-fluid">
        <!-- Brand -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="includes/contact.php">Social Hub</a>
        </div>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <?php 
                $user = $_SESSION['user_email'];
                $get_user = "SELECT * FROM users WHERE user_email='$user'"; 
                $run_user = mysqli_query($conn, $get_user);
                $row = mysqli_fetch_array($run_user);
                
                $user_id = $row['user_id']; 
                $first_name = $row['f_name'];
                $user_name = $row['user_name'];
                
                $user_posts = "SELECT * FROM posts WHERE user_id='$user_id'"; 
                $run_posts = mysqli_query($conn, $user_posts); 
                $posts = mysqli_num_rows($run_posts);
                ?>
                
                <li><a href='profile.php?u_id=<?php echo $user_id; ?>'><?php echo htmlspecialchars($first_name); ?></a></li>
                <li><a href='home.php?u_id=<?php echo $user_id; ?>'>Home</a></li>
                <li><a href="members.php">Find People</a></li>
                <li><a href='chat.php?username=<?php echo urlencode($user_name); ?>'>Messages</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        More <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href='my_post.php?u_id=<?php echo $user_id; ?>'>My Posts <span class="badge badge-secondary"><?php echo $posts; ?></span></a></li>
                        <li><a href='edit_profile.php?u_id=<?php echo urlencode($user_name); ?>'>Edit Account</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href='logout.php'>Logout</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form class="navbar-form navbar-left" method="get" action="results.php">
                        <div class="form-group">
                            <input type="text" class="form-control" name="user_query" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-info" name="search">Search</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-custom {
        background-color: #007bff; /* Navbar background color */
        border-color: #007bff; /* Border color */
        transition: background-color 0.3s; /* Smooth background color transition */
    }

    .navbar-custom:hover {
        color: #ffffff; /* White text color on hover */
        background-color: #0056b3; /* Darker background on hover */
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-nav > li > a {
        color: #ffffff; /* Text color */
        transition: color 0.3s, transform 0.3s; /* Smooth color and transform transition */
    }

    .navbar-custom .navbar-nav > li > a:hover {
        color: #ffffff; /* White color on hover */
        text-color: 0 0 10px rgba(255, 255, 255, 0.5); /* Soft white glow effect */
        transform: scale(1.05); /* Slight scale effect on hover */
    }

    .navbar-custom .dropdown-menu {
        background-color: #fff; /* Dropdown background */
        border-radius: 0; /* Remove border radius */
    }

    .navbar-custom .dropdown-menu > li > a {
        color: #333; /* Dropdown text color */
        transition: background-color 0.3s, color 0.3s; /* Smooth background color transition */
    }

    .navbar-custom .dropdown-menu > li > a:hover {
        background-color: #f0f0f0; /* Dropdown item hover background */
        color: #007bff; /* Change text color on hover */
    }

    .navbar-custom .navbar-form .form-control {
        width: 200px; /* Width for the search input */
        transition: border-color 0.3s; /* Smooth border color transition */
    }

    .navbar-custom .navbar-form .form-control:focus {
        border-color: #ffd700; /* Gold border on focus */
        box-shadow: 0 0 5px rgba(255, 215, 0, 0.5); /* Gold shadow on focus */
    }

    .navbar-custom .navbar-form .btn-info {
        border-radius: 20px; /* Rounded corners for search button */
        transition: background-color 0.3s, transform 0.3s; /* Smooth background color transition */
    }

    .navbar-custom .navbar-form .btn-info:hover {
        background-color: #0056b3; /* Darker shade on hover */
        transform: scale(1.05); /* Slightly enlarge button on hover */
    }
</style>
