<?php
// Start session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../home/login.html");
    exit();
}

$username = $_SESSION['username'];
$phone_number = $_POST['phone_number'];
$room_number = $_POST['room_number'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username_db, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cart items for the user
$sql = "SELECT * FROM cart WHERE username = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}
$stmt->close();

// Generate unique order ID
$conn->begin_transaction();
try {
    $total_price = array_sum(array_column($cart_items, 'total_price'));

    // Insert into orders table
    $order_sql = "INSERT INTO orders (restaurant_name, customer_name, total_price, phone_number, room_number, status) 
                  VALUES (?, ?, ?, ?, ?, 'Awaiting')";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("ssiss", $cart_items[0]['restaurant_name'], $username, $total_price, $phone_number, $room_number);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert into order_details table
    $detail_sql = "INSERT INTO order_details (order_id, food_name, quantity, price_per_unit) 
                   VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($detail_sql);

    foreach ($cart_items as $item) {
        $stmt->bind_param("isii", $order_id, $item['food_name'], $item['quantity'], $item['price_per_unit']);
        $stmt->execute();
    }
    $stmt->close();

    // Insert into check_for_rider table
    $rider_sql = "INSERT INTO check_for_rider (order_id, restaurant_name, customer_name, phone_number, room_number, total_price, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'Awaiting')";
    $stmt = $conn->prepare($rider_sql);
    $stmt->bind_param("issssi", $order_id, $cart_items[0]['restaurant_name'], $username, $phone_number, $room_number, $total_price);
    $stmt->execute();
    $stmt->close();

    // Update cart items to status 'Ordered'
    $update_cart_sql = "UPDATE cart SET status = 'Ordered' WHERE username = ?";
    $stmt = $conn->prepare($update_cart_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    // Redirect to confirmation page or back to menu
    header("Location: ../home/index.php");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
