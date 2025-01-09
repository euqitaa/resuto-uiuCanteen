<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_username = $_POST['username'] ?? ''; 
    $password = $_POST['password'] ?? '';

    if (empty($owner_username) || empty($password)) {
        header("Location: owner-login.html?error=emptyfields");
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

    // Query to match username (database column remains 'username')
    $sql = "SELECT id, username, password, restaurant_name FROM owners WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $owner_username); // Use owner_username from form
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $owner = $result->fetch_assoc();

        if ($password === $owner['password']) {
            $_SESSION['owner_username'] = htmlspecialchars($owner['username']); // Session uses owner_username
            $_SESSION['owner_id'] = $owner['id'];
            $_SESSION['restaurant_name'] = htmlspecialchars($owner['restaurant_name']);

            session_regenerate_id(true);
            $stmt->close();
            $conn->close();

            header("Location: owner-dash.php");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: owner-login.html?error=invalidcredentials");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: owner-login.html?error=invalidcredentials");
        exit();
    }
} else {
    header("Location: owner-login.html");
    exit();
}
?>
