<?php
// Start session
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../home/login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $username = $_SESSION['username'];
    $restaurant_name = $_POST['restaurant_name'];
    $food_name = $_POST['food_name'];
    $quantity = (int)$_POST['quantity']; // Ensure quantity is numeric
    $price_per_unit = preg_replace('/[^0-9.]/', '', $_POST['price']); // Sanitize price

    // Validate quantity
    if ($quantity <= 0) {
        echo "Invalid quantity.";
        exit();
    }

    // Insert into the cart table
    $sql = "INSERT INTO cart (username, restaurant_name, food_name, quantity, price_per_unit, status)
            VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $username, $restaurant_name, $food_name, $quantity, $price_per_unit);

    if ($stmt->execute()) {
        // Redirect back to the restaurant menu
        header("Location: restaurant-menu.php?restaurant=" . urlencode($restaurant_name));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
