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

    // Use prepared statements to check if the user exists and is not blocked
    $sql = "SELECT id, username, password_hash, is_blocked FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Check if the user is blocked
        if ($user['is_blocked'] == 1) {
            $stmt->close();
            $conn->close();
            echo "<script>alert('Your account has been blocked. Please contact support.');</script>";
            exit();
        }

        // Verify password (assuming password is hashed)
        if (password_verify($password, $user['password_hash'])) {
            // Login success: Set session variables
            $_SESSION['username'] = htmlspecialchars($user['username']);
            $_SESSION['user_id'] = $user['id'];

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Close the database connection before redirecting
            $stmt->close();
            $conn->close();

            // Redirect to index
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $stmt->close();
            $conn->close();
            header("Location: login.html?error=invalidcredentials");
            exit();
        }
    } else {
        // User not found
        $stmt->close();
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
