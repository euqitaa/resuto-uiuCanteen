<?php
// Start the session
session_start();

// Check if the user is logged in
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $sql_update = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_status, $order_id);

        if ($stmt_update->execute()) {
            header("Location: ownerorders.php?status=Pending&message=Order status updated successfully");
            exit();
        } else {
            header("Location: ownerorders.php?status=Pending&error=Failed to update order status");
            exit();
        }

        $stmt_update->close();
    } else {
        header("Location: ownerorders.php?status=Pending&error=Invalid action");
        exit();
    }
}

$conn->close();
?>
