<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.html?error=unauthorized");
    exit();
}

$conn = new mysqli("localhost", "root", "", "uiu-canteen");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurant_id'])) {
    $restaurant_id = $_POST['restaurant_id'];

    $sql_delete = "DELETE FROM restaurants WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $restaurant_id);

    if ($stmt->execute()) {
        header("Location: manage-restaurants.php?success=deleted");
    } else {
        header("Location: manage-restaurants.php?error=failed");
    }

    $stmt->close();
}
$conn->close();
?>
