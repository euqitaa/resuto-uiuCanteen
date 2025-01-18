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

// Fetch orders awaiting rider
$sql = "SELECT * FROM check_for_rider WHERE status = 'Awaiting'";
$result = $conn->query($sql);

$available_orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $available_orders[] = $row;
    }
}

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

        <div class="dashboard">
            <div class="dashboard-left">
                <p>Today's Total Earning</p>
                <p style="font-size: 2rem; padding: 0.5rem;">TK 0.00</p>
            </div>
            <div class="dashboard-right">
                <p>Today's Total Deliveries</p>
                <p style="font-size: 2rem; padding: 0.5rem;">0</p>
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
                        <form method="POST" action="take-order.php">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                            <input type="hidden" name="rider_username" value="<?php echo htmlspecialchars($rider_username); ?>">
                            <button type="submit" class="confirm-btn">Accept</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No orders awaiting delivery at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>
