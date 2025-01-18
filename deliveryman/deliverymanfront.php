<?php
// Start session
session_start();

// Redirect if not logged in
if (!isset($_SESSION['rider_username'])) {
    header("Location: delivery-login.html");
    exit();
}

$rider_username = $_SESSION['rider_username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username_db, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch rider's orders (Awaiting, Pending, Confirmed)
$sql = "SELECT * FROM check_for_rider 
        WHERE (status IN ('Pending', 'Confirmed') AND rider_username = ?) 
        OR (status = 'Awaiting' AND rider_username IS NULL)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rider_username);
$stmt->execute();
$result = $stmt->get_result();

$available_orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $available_orders[] = $row;
    }
}

$stmt->close();

// ✅ Fetch all completed deliveries and earnings (no date filter)
$sql_completed = "SELECT COUNT(*) AS total_deliveries 
                  FROM check_for_rider 
                  WHERE rider_username = ? AND status = 'Completed'";
$stmt_completed = $conn->prepare($sql_completed);
$stmt_completed->bind_param("s", $rider_username);
$stmt_completed->execute();
$result_completed = $stmt_completed->get_result();

$total_deliveries = 0;
$total_earnings = 0;

if ($result_completed->num_rows > 0) {
    $row = $result_completed->fetch_assoc();
    $total_deliveries = $row['total_deliveries'];
    $total_earnings = $total_deliveries * 25;  // 25 Taka per completed delivery
}

$stmt_completed->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="deliveryman.css">
</head>

<body>
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a href="#">レスト</a> </li>
                <li class="nav-item">👤 <?php echo htmlspecialchars($rider_username); ?></li>
                <li class="nav-item"> <a href="delivery-history.php">📃History</a> </li>
                <li class="nav-item"> <a href="delivery-logout.php">🛑Logout</a> </li>
            </ul>
        </navbar>
    </section>

    <!-- 🟢 Success/Error Message Display -->
    <?php if (isset($_SESSION['message'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin: 10px 20px; border-radius: 4px;">
            <?php 
                echo htmlspecialchars($_SESSION['message']); 
                unset($_SESSION['message']);  // Clear the message after displaying
            ?>
        </div>
    <?php endif; ?>

    <section class="main">
        <h1 class="welcome-msg">Welcome, <?php echo htmlspecialchars($rider_username); ?>!</h1>

        <!-- ✅ Updated Dashboard -->
        <div class="dashboard">
            <div class="dashboard-left">
                <p>Total Earnings</p>
                <p style="font-size: 2rem; padding: 0.5rem;">TK <?php echo number_format($total_earnings, 2); ?></p>
            </div>
            <div class="dashboard-right">
                <p>Total Deliveries</p>
                <p style="font-size: 2rem; padding: 0.5rem;"><?php echo $total_deliveries; ?></p>
            </div>
        </div>

        <div class="main-content">
            <?php if (!empty($available_orders)) : ?>
                <?php foreach ($available_orders as $order) : ?>
                    <div class="order-box">
                        <p>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
                        <p>Customer: <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p>Phone: <?php echo htmlspecialchars($order['phone_number']); ?></p>
                        <p>Total Price: TK <?php echo htmlspecialchars($order['total_price']); ?></p>

                        <?php if ($order['status'] === 'Awaiting') : ?>
                            <form method="POST" action="take-order.php">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <button type="submit" class="confirm-btn">Accept</button>
                            </form>
                        <?php elseif ($order['status'] === 'Pending') : ?>
                            <button class="confirm-btn" disabled>Pending (Waiting for Restaurant Confirmation)</button>
                        <?php elseif ($order['status'] === 'Confirmed') : ?>
                            <form method="POST" action="complete-order.php">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <button type="submit" class="confirm-btn">Mark as Completed</button>
                            </form>
                        <?php elseif ($order['status'] === 'Completed') : ?>
                            <button class="confirm-btn" disabled>Completed</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No orders available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>
