<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['restaurant_name'])) {
    header("Location: ../ownerdash/owner-login.html");
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

// Fetch restaurant name
$restaurant_name = $_SESSION['restaurant_name'];

// Determine which orders to show based on the status filter
$order_status = isset($_GET['status']) ? $_GET['status'] : 'Pending'; // Default to Pending
$sql_orders = "SELECT * FROM orders WHERE restaurant_name = ? AND status = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("ss", $restaurant_name, $order_status);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - レスト</title>
    <link rel="stylesheet" href="ownerorders-style.css">
    <style>
        .status-pending {
            color: green;
        }

        .status-confirmed {
            color: orange;
        }

        .status-completed {
            color: blue;
        }

        .status-cancelled {
            color: red;
        }

        .order-actions {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div>
                <a href="../ownerdash/owner-dash.php" class="branding">レスト</a><br>
                <a href="../ownerdash/owner-dash.php" class="company-name"><?php echo htmlspecialchars($restaurant_name); ?></a>
            </div>

            <div class="sidebar-buttons">
                <button class="sidebar-btn"><a href="ownerorders.php?status=Pending" style="text-decoration: none; color: inherit;">Pending</a></button>
                <button class="sidebar-btn"><a href="ownerorders.php?status=Confirmed" style="text-decoration: none; color: inherit;">Confirmed</a></button>
                <button class="sidebar-btn"><a href="ownerorders.php?status=Completed" style="text-decoration: none; color: inherit;">Completed</a></button>
                <button class="sidebar-btn"><a href="ownerorders.php?status=Cancelled" style="text-decoration: none; color: inherit;">Cancelled</a></button>
            </div>
            <div class="sidebar-support">
                <button class="support-btn">Support</button>
            </div>
        </div>

        <div class="content">
            <h1 style="opacity: 0.6; color: #1b1f46; padding-top: 1rem; padding-left: 1rem;">Orders Management</h1>
            <div class="orders-list">
                <div class="order-bottom">
                    <ul class="orderlist">
                        <?php
                        if ($result_orders->num_rows > 0) {
                            while ($order = $result_orders->fetch_assoc()) {
                                ?>
                                <li class="orderlist-ele">
                                    <div>
                                        <p style="padding-bottom:0.5rem; opacity: 0.7;">Order number: <label for=""><?php echo $order['id']; ?></label></p>
                                        <p>Customer name: <label for=""><?php echo htmlspecialchars($order['customer_name']); ?></label></p>
                                        <p>Order total: <label for="">Tk <?php echo htmlspecialchars($order['total_price']); ?></label></p>
                                    </div>
                                    <div>
                                        <p style="padding-bottom:0.5rem; opacity: 0.7;">Order details:</p>
                                        <!-- Fetch order details -->
                                        <?php
                                        $order_id = $order['id'];
                                        $sql_order_details = "SELECT * FROM order_details WHERE order_id = ?";
                                        $stmt_order_details = $conn->prepare($sql_order_details);
                                        $stmt_order_details->bind_param("i", $order_id);
                                        $stmt_order_details->execute();
                                        $result_order_details = $stmt_order_details->get_result();

                                        while ($item = $result_order_details->fetch_assoc()) {
                                            echo "<p style='padding-bottom:0.5rem;'>• " . htmlspecialchars($item['food_name']) . " x" . htmlspecialchars($item['quantity']) . " - " . htmlspecialchars($item['quantity'] * $item['price_per_unit']) . " Tk</p>";
                                        }
                                        ?>
                                    </div>
                                    <div>
                                        <p style="padding-bottom:0.5rem;">Order status: 
                                            <?php 
                                            if ($order['status'] === 'Pending') { ?>
                                                <span class="status-pending">Pending</span>
                                            <?php } elseif ($order['status'] === 'Confirmed') { ?>
                                                <span class="status-confirmed">Confirmed</span>
                                            <?php } elseif ($order['status'] === 'Completed') { ?>
                                                <span class="status-completed">Completed</span>
                                            <?php } elseif ($order['status'] === 'Cancelled') { ?>
                                                <span class="status-cancelled">Cancelled</span>
                                            <?php } ?>
                                        </p>
                                    </div>
                                    <?php if ($order['status'] === 'Pending') { ?>
                                        <div class="order-actions">
                                            <form method="POST" action="update-order-status.php">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <button type="submit" name="action" value="Confirm" class="confirm-btn">Confirm</button>
                                                <button type="submit" name="action" value="Decline" class="decline-btn">Decline</button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </li>
                                <?php
                            }
                        } else {
                            echo "<p>No orders found for this status.</p>";
                        }
                        $stmt_orders->close();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
