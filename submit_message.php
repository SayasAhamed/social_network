<?php
session_start();
include('includes/connection.php');

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and prepare inputs
    $sender = trim($_POST['sender']);
    $receiver = trim($_POST['receiver']);
    $message = trim($_POST['message']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO user_messages (sender, receiver, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender, $receiver, $message);

    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
