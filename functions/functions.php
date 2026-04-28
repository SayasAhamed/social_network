<?php
// Establish connection
$conn = mysqli_connect("localhost", "root", "", "social_network") or die("Connection was not established");

// Insert post function
if (!function_exists('insertPost')){
    function insertPost(){
        global $conn;
        global $user_id;

        if(isset($_POST['sub'])){
            $content = htmlentities($_POST['content']);
            $upload_image = $_FILES['upload_image']['name'];
            $image_tmp = $_FILES['upload_image']['tmp_name'];
            $random_number = rand(1, 100);

            if(strlen($content) > 250){
                echo "<script>alert('Please use 250 or fewer words!')</script>";
                echo "<script>window.open('home.php', '_self')</script>";
                return;
            }

            if(strlen($upload_image) > 0 && strlen($content) > 0){
                move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number");
                $insert = "INSERT INTO posts (user_id, post_content, upload_image, post_date) VALUES ('$user_id', '$content', '$upload_image.$random_number', NOW())";
            } elseif(strlen($upload_image) > 0) {
                move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number");
                $insert = "INSERT INTO posts (user_id, post_content, upload_image, post_date) VALUES ('$user_id', 'No', '$upload_image.$random_number', NOW())";
            } elseif(strlen($content) > 0) {
                $insert = "INSERT INTO posts (user_id, post_content, post_date) VALUES ('$user_id', '$content', NOW())";
            } else {
                echo "<script>alert('Error occurred while uploading!')</script>";
                echo "<script>window.open('home.php', '_self')</script>";
                return;
            }

            $run = mysqli_query($conn, $insert);

            if($run){
                echo "<script>alert('Your post was updated a moment ago!')</script>";
                echo "<script>window.open('home.php', '_self')</script>";

                $update = "UPDATE users SET posts='yes' WHERE user_id='$user_id'";
                mysqli_query($conn, $update);
            }

           
        }
    }


// Get posts function
function get_posts(){
    global $conn;
    $per_page = 10;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $per_page;
    $get_posts = "SELECT * FROM posts ORDER BY post_date DESC LIMIT $start_from, $per_page";
    $run_posts = mysqli_query($conn, $get_posts);

    while($row_posts = mysqli_fetch_array($run_posts)){
        $post_id = $row_posts['post_id'];
        $user_id = $row_posts['user_id'];
        $content = substr($row_posts['post_content'], 0, 40);
        $upload_image = $row_posts['upload_image'];
        $post_date = $row_posts['post_date'];

        $user = "SELECT * FROM users WHERE user_id='$user_id' AND posts='yes'";
        $run_user = mysqli_query($conn, $user);
        $row_user = mysqli_fetch_array($run_user);

        $user_name = $row_user['user_name'];
        $user_image = $row_user['user_image'];

        echo "
        <div class='row'>
            <div class='col-sm-3'></div>
            <div id='posts' class='col-sm-6' style='background-color: #d3e2ed; border-radius: 10px; margin-top: 10px; margin-bottom: 10px; rgba(0, 0, 0, 0.3); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                <div class='row'>
                    <div class='col-sm-2'>
                        <p><a class='user-link' href='user_profile.php?u_id=$user_id'><img src='users/$user_image' class='img-circle user-img' style='object-fit: cover; border-radius: 50%; margin-bottom:25px; margin-top:10px; width: 90px; height: 90px; margin-top: 15px; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer; transform: scale(1.1); box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);'></p></a>
                    </div>
                    <div class='col-sm-6'>
                        <h3><a style='margin-left: 40px;' class='user-link' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                        <h4 style='margin-left: 40px;'><small class='post-date'>Updated a post on <strong>$post_date</strong></small></h4>
                    </div>
                    <div class='col-sm-4'></div>
                </div>
                <div class='row'>
                    <div class='col-sm-12'>";

        if($content == "No" && strlen($upload_image) > 0){
            echo "<img id='posts-img' src='imagepost/$upload_image' style='object-fit:cover; height:auto; width:50%;' >";
        } elseif(strlen($content) > 0 && strlen($upload_image) > 0){
            echo "<p>$content</p><img id='posts-img' src='imagepost/$upload_image' style='object-fit: cover; width:100px; height:auto;'>";
        } else {
            echo "<h3><p>$content</p></h3>";
        }

        echo "
                    </div>
                </div><br>
                <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
            </div>
            <div class='col-sm-3'></div>
        </div><br><br>";
    }

    include("pagination.php");
}



// Single post function
function single_post() {
    global $conn;

    if (isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];

        // Fetch the post
        $get_posts = "SELECT * FROM posts WHERE post_id='$post_id'";
        $run_posts = mysqli_query($conn, $get_posts);
        $row_posts = mysqli_fetch_array($run_posts);

        if (!$row_posts) {
            echo "<script>alert('Post not found.')</script>";
            return;
        }

        $user_id = $row_posts["user_id"];
        $content = $row_posts["post_content"];
        $upload_image = $row_posts["upload_image"];
        $post_date = $row_posts["post_date"];

        // Fetch the user who made the post
        $user = "SELECT * FROM users WHERE user_id='$user_id' AND posts='yes'";
        $run_user = mysqli_query($conn, $user);
        $row_user = mysqli_fetch_array($run_user);

        if (!$row_user) {
            $user_name = "Unknown";
            $user_image = "default.png"; // Or a placeholder image
        } else {
            $user_name = $row_user["user_name"];
            $user_image = $row_user["user_image"];
        }

        // Display the single post
        echo "
        <div class='row'>
            <div class='col-sm-3'></div>
            <div id='posts' class='col-sm-6' style='background-color: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <div class='row'>
                    <div class='col-sm-2'>
                        <img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px;'>
                    </div>
                    <div class='col-sm-6'>
                        <h3 style='margin-left:20px;'><a style='text-decoration:none; color: #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                        <h4 style='margin-left:20px;'><small style='color:gray;'>Updated a post on <strong>$post_date</strong></small></h4>
                    </div><br><br>
                </div>
                <div class='row'>
                    <div class='col-sm-12'>
        ";

        if ($content == "No" && strlen($upload_image) > 0) {
            echo "<img id='posts-img' src='imagepost/$upload_image' style='object-fit:cover; height:auto; width:100%; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1);'>";
        } elseif (strlen($content) > 0 && strlen($upload_image) > 0) {
            echo "<p style='margin-top:20px; margin-left:10px; font-weight:bold;'>$content</p><img id='posts-img' src='imagepost/$upload_image' style='object-fit: cover; width:100%; height:auto; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1);'>";
        } else {
            echo "<p style='font-weight:bold;'>$content</p>";
        }

        echo "
                    </div>
                </div>
                <br>
            </div>
            <div class='col-sm-3'></div>
        </div>
        <br><br>";

        // Handle comment submission
        if (isset($_POST['reply'])) {
            $comment = htmlentities($_POST['comment']);

            if (!isset($_SESSION['user_email'])) {
                echo "<script>alert('You need to be logged in to comment.')</script>";
                echo "<script>window.open('index.php', '_self')</script>";
                return;
            }

            $user_com = $_SESSION['user_email'];
            $get_user_com = "SELECT * FROM users WHERE user_email='$user_com'";
            $run_user_com = mysqli_query($conn, $get_user_com);
            $row_user_com = mysqli_fetch_array($run_user_com);

            if (!$row_user_com) {
                echo "<script>alert('User not found.')</script>";
                return;
            }

            $user_com_id = $row_user_com['user_id'];

            if ($comment == "") {
                echo "<script>alert('Enter your comment!')</script>";
                echo "<script>window.open('single.php?post_id=$post_id', '_self')</script>";
            } else {
                $insert = "INSERT INTO comments (post_id, user_id, comment, comment_author, date) VALUES ('$post_id', '$user_com_id', '$comment', '$user_com', NOW())";
                $run = mysqli_query($conn, $insert);

                if ($run) {
                    echo "<script>alert('Your comment was added!')</script>";
                    echo "<script>window.open('single.php?post_id=$post_id', '_self')</script>";
                } else {
                    echo "<script>alert('Error adding comment.')</script>";
                }
            }
        }

        // Display comments
        $get_com = "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY date DESC";
        $run_com = mysqli_query($conn, $get_com);

        if (!$run_com) {
            echo "<script>alert('Error fetching comments.')</script>";
            return;
        }

        while ($row_com = mysqli_fetch_array($run_com)) {
            $comment_id = $row_com['com_id'];
            $comment_author = $row_com['comment_author'];
            $comment_content = $row_com['comment'];
            $comment_date = $row_com['date'];

            // Fetch comment author image
            $comment_author_id = $row_com['user_id'];
            $comment_user = "SELECT * FROM users WHERE user_id='$comment_author_id'";
            $run_comment_user = mysqli_query($conn, $comment_user);
            $row_comment_user = mysqli_fetch_array($run_comment_user);
            $comment_user_image = $row_comment_user['user_image'] ?? 'default.png'; // Default if not found
            $comment_user_name = $row_comment_user['user_name'] ?? 'Unknown'; // Default if not found

            echo "
            <div class='row'>
                <div class='col-sm-3'></div>
                <div class='col-sm-6'>
                    <div class='media'>
                        <div class='media-left'>
                            <img src='users/$comment_user_image' class='media-object' style='object-fit:cover; width:50px; height:50px;'>
                        </div>
                        <div class='media-body'>
                            <h4 class='media-heading'>$comment_user_name <small>$comment_date</small></h4>
                            <p style='margin-top:10px; margin-left:5px;font-weight:bold;'>$comment_content</p><br>
                        </div>
                    </div>
                </div>
                <div class='col-sm-3'></div>
            </div>
            <br>";
        }
    }
}

// Get User post
function user_post(){
    global $conn;

    if(isset($_GET['u_id'])){
        $u_id = $_GET['u_id'];
    
    }
    $get_posts = "SELECT * FROM posts WHERE user_id='$u_id' ORDER BY 1 DESC LIMIT 5";

    $run_posts = mysqli_query($conn, $get_posts);

    while($row_posts=mysqli_fetch_array($run_posts)){
        $post_id = $row_posts['post_id'];
        $user_id = $row_posts['user_id'];
        $content = $row_posts['post_content'];
        $post_image = $row_posts['upload_image'];
        $post_date = $row_posts['post_date'];

        $user = "SELECT * FROM users WHERE user_id='$user_id' AND posts='yes'";

        $run_user = mysqli_query($conn, $user);
        $row_user = mysqli_fetch_array($run_user);

        $user_name = $row_user['user_name'];
        $user_image = $row_user['user_image'];

        if(isset($_GET['u_id'])){
            $u_id = $_GET['u_id'];
        }
        $getuser = "SELECT user_email FROM users WHERE user_id='$u_id'";
        $run_user = mysqli_query($conn, $getuser);
        $row = mysqli_fetch_array($run_user);

        $user_email = $row['user_email'];

        $user = $_SESSION['user_email'];
        $get_user = "SELECT * FROM users WHERE user_email='$user'";
        $run_user = mysqli_query($conn, $get_user);
        $row = mysqli_fetch_array($run_user);

        $user_id = $row['user_id'];
        $u_email = $row['user_email'];

        if($u_email != $user_email){
            echo" <script>window.open('my_post.php?u_id=$user_id', '_self')</script>";
        }
        else{
            if($content=="No" && strlen($post_image) >= 1){
                echo"
                <div class='row'>
                    <div class='col-sm-3'>
                    </div>
                    <div id='posts' class='col-sm-6'>
                        <div class='row'>
                            <div class='col-sm-2'>
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px; object-fit:cover;'></p>
                            </div>
                            <div class='col-sm-6'>
                                <h3 style='margin-left:40px;'><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                <h4 style='margin-left:40px;'><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                            <div class='col-sm-4'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <img id='posts-img' src='imagepost/$post_image' style='object-fit:cover; height:auto; width:50%;' >
                            </div>
                        </div><br>
                        <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>View</button></a><br>
                    </div>
                    <div class='col-sm-3'>
                    </div>
                </div><br><br>
                ";
            }
    
            else if(strlen($content) >= 1 && strlen($post_image) >= 1){
                echo"
                <div class='row'>
                    <div class='col-sm-3'>
                    </div>
                    <div id='posts' class='col-sm-6'>
                        <div class='row'>
                            <div class='col-sm-2'>
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px; object-fit:cover;'></p>
                            </div>
                            <div class='col-sm-6'>
                                <h3 style='margin-left:40px;'><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                <h4 style='margin-left:40px;'><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                            <div class='col-sm-4'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <p style='float:left;margin-left:10px;font-weight:bold;'>$content</p>
                                <img id='posts-img' src='imagepost/$post_image' style='object-fit: cover; width:100px; height:auto; object-fit:cover;'>
                            </div>
                        </div><br>
                        <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>View</button></a><br>
                    </div>
                    <div class='col-sm-3'>
                    </div>
                </div><br><br>
                ";
            }
    
            else{
                echo"
                <div class='row'>
                    <div class='col-sm-3'>
                    </div>
                    <div id='posts' class='col-sm-6'>
                        <div class='row'>
                            <div class='col-sm-2'>
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px; object-fit:cover;'></p>
                            </div>
                            <div class='col-sm-6'>
                                <h3 style='margin-left:40px;'><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                <h4 style='margin-left:40px;'><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                            </div>
                            <div class='col-sm-4'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <h3 style='font-weight:bold;float:left; margin-left:10px;'><p>$content</p></h3>
                            </div>
                            <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info' style='margin-bottom:-20px;'>View</button></a><br>
                        </div><br>
                         
                    </div>
                    <div class='col-sm-3'>
                    </div>
                </div><br><br>
                ";
            }
        }
           
        }
}

//User search function
function results(){
    global $conn;

    if(isset($_GET['search'])){
        $search_query = strtolower(htmlentities($_GET['user_query']));
    }

    $get_posts = "
        SELECT posts.post_id, posts.user_id, posts.post_content, posts.upload_image, posts.post_date, users.user_name, users.user_image 
        FROM posts 
        JOIN users ON posts.user_id = users.user_id 
        WHERE LOWER(posts.post_content) LIKE '%$search_query%' 
        OR LOWER(posts.upload_image) LIKE '%$search_query%' 
        OR LOWER(users.user_name) LIKE '%$search_query%'
    ";

    $run_posts = mysqli_query($conn, $get_posts);

    while($row_posts = mysqli_fetch_array($run_posts)){
        $post_id = $row_posts['post_id'];
        $user_id = $row_posts['user_id'];
        $post_date = $row_posts['post_date'];
        $content = $row_posts['post_content'];
        $upload_image = $row_posts['upload_image'];
        $user_name = $row_posts['user_name'];
        $user_image = $row_posts['user_image'];

        // Displaying the POSTS
        if($content == "No" && strlen($upload_image) >= 1){
            echo "
            <div class='row'>
                <div class='col-sm-3'></div>
                <div id='posts' class='col-sm-6'style='background-color: #d3e2ed; border-radius: 10px; margin-top: 10px; margin-bottom: 10px; rgba(0, 0, 0, 0.3); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    <div class='row'>
                        <div class='col-sm-2' >
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px;'></p>
                        </div>
                        <div class='col-sm-6'>
                            <h3 style='margin-left:50px;'><a style='text-decoration:none; cursor:pointer; color:#3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                            <h4 style='margin-left:20px;'><small style='color:black;'>Updated a post on <br><strong>$post_date</strong></small></h4>
                        </div>
                        <div class='col-sm-4'></div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <img id='posts-img' src='imagepost/$upload_image' style='object-fit:cover; height:auto; width:50%;'>
                        </div>
                    </div><br>
                    <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>View</button></a><br>
                </div>
                <div class='col-sm-3'></div>
            </div><br><br>";
        } else if(strlen($content) >= 1 && strlen($upload_image) >= 1){
            echo "
            <div class='row'>
                <div class='col-sm-3'></div>
                <div id='posts' class='col-sm-6' style='background-color: #d3e2ed; border-radius: 10px; margin-top: 10px; margin-bottom: 10px; rgba(0, 0, 0, 0.3); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    <div class='row'>
                        <div class='col-sm-2'>
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px;'></p>
                        </div>
                        <div class='col-sm-6'>
                            <h3 style='margin-left:50px;'><a style='text-decoration:none; cursor:pointer; color:#3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                            <h4 style='margin-left:20px;'><small style='color:black;'>Updated a post on <br><strong>$post_date</strong></small></h4>
                        </div>
                        <div class='col-sm-4'></div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <p><strong>$content</strong></p>
                            <img id='posts-img' src='imagepost/$upload_image' style='object-fit: cover; width:100%; height:auto;'>
                        </div>
                    </div><br>
                    <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>View</button></a><br>
                </div>
                <div class='col-sm-3'></div>
            </div><br><br>";
        } else {
            echo "
            <div class='row'>
                <div class='col-sm-3'></div>
                <div id='posts' class='col-sm-6' style='background-color: #d3e2ed; border-radius: 10px; margin-top: 10px; margin-bottom: 10px; rgba(0, 0, 0, 0.3); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    <div class='row'>
                        <div class='col-sm-2'>
                            <p><img src='users/$user_image' class='img-circle' style='object-fit: cover; width:100px; height:100px;'></p>
                        </div>
                        <div class='col-sm-6'>
                            <h3 style='margin-left:50px;'><a style='text-decoration:none; cursor:pointer; color:#3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                            <h4 style='margin-left:20px;'><small style='color:black;'>Updated a post on <br><strong>$post_date</strong></small></h4>
                        </div>
                        <div class='col-sm-4'></div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <h3><p><strong>$content</strong></p></h3>
                        </div>
                    </div><br>
                    <a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>View</button></a><br>
                </div>
                <div class='col-sm-3'></div>
            </div><br><br>";
        }
    }
}


    //User search_posts
    function  search_user() {
        global $conn;

        if(isset($_GET['search_user_btn'])){
            $search_query = strtolower(htmlentities($_GET['search_user']));
            $get_user = "SELECT * FROM users WHERE LOWER(f_name) LIKE '%$search_query%' OR LOWER(l_name) LIKE '%$search_query%' OR LOWER(user_name) LIKE '%$search_query%'";

        }
        else {
            $get_user = "SELECT * FROM users";
        }

        $run_user = mysqli_query($conn, $get_user);

        while($row_user = mysqli_fetch_array($run_user)){
            $user_id = $row_user['user_id'];
            $f_name = $row_user['f_name'];
            $l_name = $row_user['l_name'];
            $username = $row_user['user_name'];
            $user_image = $row_user['user_image'];

            echo "
            <div class='row'>
                <div class='col-sm-3'>
                </div>
                <div class='col-sm-6'>
                    <div class='row' id='find_peple' style='background-color: #d3e2ed; border-radius: 10px; margin-top: 10px; margin-bottom: 10px; rgba(0, 0, 0, 0.3); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                        <div class='col-sm-4>
                            <a href ='user_profile.php?u_id=$user_id'>
                            <img src='users/$user_image' width='140px' height='150px' title='$username' style='float:left; margin:1px; object-fit:cover;'/>
                            </a>
                        </div><br><br>
                        <div class ='col-sm-6'>
                            <a style='text-decoration:none; cursor:pointer; color:#3897f0;' href ='user_profile.php?u_id=$user_id'><strong><h2>$f_name $l_name</h2></strong>
                            </a>
                        </div>
                        <div class='col-sm-3'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-4'>
                </div>
            </div><br>
            
            ";
        
        }


}
}
?>
