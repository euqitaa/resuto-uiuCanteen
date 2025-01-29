<?php
// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check for empty fields
    if (empty($username) || empty($password)) {
        header("Location: login.html?error=emptyfields");
        exit();
    }

    // Database connection
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "uiu-canteen";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate admin credentials
    $sql = "SELECT id, username, password FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Successful login
        $admin = $result->fetch_assoc();
        session_regenerate_id(true);
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_id'] = $admin['id'];

        $stmt->close();
        $conn->close();

        header("Location: admin.php");
        exit();
    } else {
        // Invalid credentials
        $stmt->close();
        $conn->close();
        header("Location: login.html?error=invalidcredentials");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
