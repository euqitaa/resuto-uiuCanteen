<?php
// Start session
session_start();

// Check if the restaurant owner is logged in
if (!isset($_SESSION['restaurant_name'])) {
    header("Location: owner-login.html");
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

// Handle the order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    // Determine the new status
    $new_status = '';
    if ($action === 'Confirm') {
        $new_status = 'Confirmed';
    } elseif ($action === 'Decline') {
        $new_status = 'Cancelled';
    }

    if (!empty($new_status)) {
        // ✅ Update 'orders' table
        $sql_update_orders = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt_orders = $conn->prepare($sql_update_orders);
        $stmt_orders->bind_param("si", $new_status, $order_id);
        
        // ✅ Update 'check_for_rider' table
        $sql_update_rider = "UPDATE check_for_rider SET status = ? WHERE order_id = ?";
        $stmt_rider = $conn->prepare($sql_update_rider);
        $stmt_rider->bind_param("si", $new_status, $order_id);

        // Execute both updates
        if ($stmt_orders->execute() && $stmt_rider->execute()) {
            $_SESSION['message'] = "✅ Order ID $order_id has been updated to $new_status.";
        } else {
            $_SESSION['message'] = "❌ Failed to update Order ID $order_id.";
        }

        $stmt_orders->close();
        $stmt_rider->close();

        header("Location: ownerorders.php?status=Pending");
        exit();
    } else {
        $_SESSION['message'] = "❌ Invalid action.";
        header("Location: ownerorders.php?status=Pending");
        exit();
    }
}

$conn->close();
?>
