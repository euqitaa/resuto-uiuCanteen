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
        header("Location: owner-login.html?error=emptyfields");
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

    // Use prepared statements to check if the owner exists
    $sql = "SELECT id, username, password, restaurant_name FROM owners WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the owner's data
        $owner = $result->fetch_assoc();

        // Verify password
        if ($password === $owner['password']) {
            // Login success: Set session variables
            $_SESSION['username'] = htmlspecialchars($owner['username']);
            $_SESSION['owner_id'] = $owner['id'];
            $_SESSION['restaurant_name'] = htmlspecialchars($owner['restaurant_name']);

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Close the database connection before redirecting
            $stmt->close();
            $conn->close();

            // Redirect to owner dashboard
            header("Location: owner-dash.html");
            exit();
        } else {
            // Incorrect password
            $stmt->close();
            $conn->close();
            header("Location: owner-login.html?error=invalidcredentials");
            exit();
        }
    } else {
        // Owner not found
        $stmt->close();
        $conn->close();
        header("Location: owner-login.html?error=invalidcredentials");
        exit();
    }
} else {
    // Redirect if accessed without submitting the form
    header("Location: owner-login.html");
    exit();
}
?>
