<?php
session_start();

if (!isset($_SESSION['rider_username'])) {
    header("Location: delivery-login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $rider_username = $_SESSION['rider_username'];

    $sql_update = "UPDATE check_for_rider SET rider_username = ?, status = 'Pending' WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $rider_username, $order_id);

    if ($stmt_update->execute()) {
        $sql_orders = "UPDATE orders SET status = 'Pending' WHERE id = ?";
        $stmt_orders = $conn->prepare($sql_orders);
        $stmt_orders->bind_param("i", $order_id);
        $stmt_orders->execute();
        $stmt_orders->close();
    }

    $stmt_update->close();
}
$conn->close();

header("Location: deliverymanfront.php");
exit();
?>
