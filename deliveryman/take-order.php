<?php
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

// Check if the request is POST and order_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $rider_username = $_SESSION['rider_username'];

    // Prevent double assignment: update only if status is 'Awaiting'
    $sql_update = "UPDATE check_for_rider 
                   SET rider_username = ?, status = 'Pending' 
                   WHERE order_id = ? AND status = 'Awaiting'";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $rider_username, $order_id);

    if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
        // Sync the status with the 'orders' table
        $sql_orders = "UPDATE orders SET status = 'Pending' WHERE id = ?";
        $stmt_orders = $conn->prepare($sql_orders);
        $stmt_orders->bind_param("i", $order_id);
        $stmt_orders->execute();
        $stmt_orders->close();

        // Success message
        $_SESSION['message'] = "✅ Order ID $order_id has been accepted successfully!";
    } else {
        // If no rows were updated, the order was already taken
        $_SESSION['message'] = "⚠️ Order ID $order_id was already accepted by another rider.";
    }

    $stmt_update->close();
} else {
    // Invalid access attempt
    $_SESSION['message'] = "❌ Invalid request.";
}

// Close connection and redirect
$conn->close();
header("Location: deliverymanfront.php");
exit();
?>
