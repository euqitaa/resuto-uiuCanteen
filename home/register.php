<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $uniId = $_POST['uniId'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Validate inputs
    if (empty($uniId) || empty($username) || empty($password) || empty($confirmPassword)) {
        header("Location: register.html?error=emptyfields");
        exit();
    }

    if ($password !== $confirmPassword) {
        header("Location: register.html?error=passwordmismatch");
        exit();
    }

    // Validate University ID (numeric only)
    if (!ctype_digit($uniId)) {
        header("Location: register.html?error=invaliduniid");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        $stmt->close();
        $conn->close();
        header("Location: register.html?error=userexists");
        exit();
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password_hash) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        // Registration successful
        $stmt->close();
        $conn->close();
        header("Location: login.html?success=registered");
        exit();
    } else {
        // Database error
        $stmt->close();
        $conn->close();
        header("Location: register.html?error=dberror");
        exit();
    }
} else {
    // Redirect if accessed without submitting the form
    header("Location: register.html");
    exit();
}
?>
