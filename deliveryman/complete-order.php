<?php
// Start session
session_start();

// Check if the rider is logged in
if (!isset($_SESSION['rider_username'])) {
    header("Location: delivery-login.html");
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

// Handle the "Mark as Completed" action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $rider_username = $_SESSION['rider_username'];

    // ✅ Update status to 'Completed' in check_for_rider
    $sql_update_rider = "UPDATE check_for_rider 
                         SET status = 'Completed' 
                         WHERE order_id = ? AND rider_username = ?";
    $stmt_rider = $conn->prepare($sql_update_rider);
    $stmt_rider->bind_param("is", $order_id, $rider_username);

    // ✅ Update status to 'Completed' in orders
    $sql_update_order = "UPDATE orders 
                         SET status = 'Completed' 
                         WHERE id = ?";
    $stmt_order = $conn->prepare($sql_update_order);
    $stmt_order->bind_param("i", $order_id);

    // ✅ Execute both updates
    if ($stmt_rider->execute() && $stmt_order->execute()) {
        $_SESSION['message'] = "✅ Order ID $order_id has been marked as Completed.";
    } else {
        $_SESSION['message'] = "❌ Failed to update Order ID $order_id.";
    }

    $stmt_rider->close();
    $stmt_order->close();
}

$conn->close();

// Redirect back to the delivery dashboard
header("Location: deliverymanfront.php");
exit();
?>
