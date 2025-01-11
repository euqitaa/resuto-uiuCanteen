<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check credentials
    $sql = "SELECT * FROM riders WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Successful login
        $_SESSION['rider_username'] = $username;
        header("Location: rider-dashboard.php"); // Redirect to rider dashboard
        exit();
    } else {
        // Invalid credentials
        header("Location: delivery-login.html?error=Invalid%20credentials");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
