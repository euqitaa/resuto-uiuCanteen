<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $quantity = $_POST['food_quantity'];
    $availability = $_POST['availability'];

    $sql = "UPDATE restaurants SET food_quantity = ?, availability = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $quantity, $availability, $id);

    if ($stmt->execute()) {
        header("Location: menumanage.php?success=1");
        exit();
    } else {
        echo "Error updating menu: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
