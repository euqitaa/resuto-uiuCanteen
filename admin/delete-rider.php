<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: login.html?error=unauthorized");
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

// Delete rider
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rider_id'])) {
    $rider_id = $_POST['rider_id'];

    $sql_delete = "DELETE FROM riders WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $rider_id);

    if ($stmt->execute()) {
        header("Location: manage-riders.php?success=deleted");
    } else {
        header("Location: manage-riders.php?error=failed");
    }

    $stmt->close();
}

$conn->close();
?>
