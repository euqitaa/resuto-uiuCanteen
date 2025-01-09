<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        header("Location: login.html?error=emptyfields");
        exit();
    }

    // Connect to the database
    $servername = "localhost";
    $db_username = "root"; // Default XAMPP MySQL username
    $db_password = "";     // Default XAMPP MySQL password
    $dbname = "uiu-canteen";   // Replace with your database name

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Query to check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify password (assuming password is hashed)
        if (password_verify($password, $user['password'])) {
            // Login success: Set session variables
            $_SESSION['username'] = $user['username']; // Changed 'name' to 'username'
            $_SESSION['user_id'] = $user['id'];

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Close the database connection before redirecting
            $conn->close();

            // Redirect to index
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $conn->close();
            header("Location: login.html?error=invalidcredentials");
            exit();
        }
    } else {
        // User not found
        $conn->close();
        header("Location: login.html?error=invalidcredentials");
        exit();
    }
} else {
    // Redirect if accessed without submitting the form
    header("Location: login.html");
    exit();
}
?>
