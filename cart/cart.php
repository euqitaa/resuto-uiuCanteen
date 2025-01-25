<?php
// Start session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../home/login.html");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username_db, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has any active orders
$active_order_sql = "SELECT id FROM orders WHERE customer_name = ? AND status IN ('Awaiting', 'Pending', 'Confirmed')";
$stmt = $conn->prepare($active_order_sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$active_order_result = $stmt->get_result();
$has_active_order = $active_order_result->num_rows > 0; // True if active orders exist
$stmt->close();

// Fetch cart items only if no active orders exist
$cart_items = [];
if (!$has_active_order) {
    $sql = "SELECT c.id AS cart_id, c.food_name, c.quantity, c.price_per_unit, c.total_price, r.food_image 
            FROM cart c 
            INNER JOIN restaurants r 
            ON c.restaurant_name = r.restaurant_name AND c.food_name = r.food_name 
            WHERE c.username = ? AND c.status = 'Pending'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - レスト</title>
    <link href="cart-style.css" rel="stylesheet">
    <link href="..\home\landing-style.css" rel="stylesheet">
    <style>
        /* Existing CSS from cart.php */
        .checkout {
            width: 30%;
            border-radius: 1rem;
            background: #fff;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding: 1.5rem;
            margin-left: 20px;
            align-self: flex-start;
            margin-top: 115px;
        }

        .checkout h2 {
            font-size: 20px;
            margin-bottom: 1rem;
            font-weight: bold;
            text-align: left;
        }

        .checkout-box {
            position: inherit;
            border-radius: 0.5rem;
            background-color: transparent;
            padding: 1rem;
        }

        .checkout-top {
            padding: 0.5rem 0;
        }

        #phone-number,
        #room-number {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        #checkout-btn {
            width: 100%;
            background-color: #1b1f46;
            color: white;
            border: none;
            padding: 0.7rem;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        #checkout-btn:hover {
            background-color: #fe4119;
        }

        .checkout-bottom-food,
        .checkout-bottom-userinfo {
            padding: 1rem;
        }

        .checkout-bottom {
            padding-bottom: 1rem;
        }

        .checkout-bottom-btn {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 0;
        }

        /* New CSS for active order message */
        .active-order-message {
            color: red;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }

        /* Disabled button styling */
        .btn-area:disabled,
        #checkout-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .btn-area:disabled:hover,
        #checkout-btn:disabled:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a href="../home/index.php">レスト</a> </li>
                <li class="nav-item"> <a href="">📌Location</a> </li>
                <li class="nav-item">👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li class="nav-item"> <a href="cart.php">🛒Cart</a></li>
            </ul>
        </navbar>
    </section>

    <div class="wrapper">
        <div class="cart">
            <h1>🛒 Your Cart</h1>
            <div class="project">
                <?php if ($has_active_order) : ?>
                    <!-- Display message if user has an active order -->
                    <p class="active-order-message">You already have an active order. Please wait until it is completed or cancelled.</p>
                <?php elseif (!empty($cart_items)) : ?>
                    <!-- Display cart items if no active orders exist -->
                    <div class="shop">
                        <?php foreach ($cart_items as $item) : ?>
                            <div class="box">
                                <img src="<?php echo htmlspecialchars($item['food_image']); ?>" alt="Food Image">
                                <div class="content">
                                    <h3><?php echo htmlspecialchars($item['food_name']); ?></h3>
                                    <h4><?php echo htmlspecialchars($item['price_per_unit']); ?> Tk</h4>
                                    <p class="unit">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                    <form method="POST" action="remove-from-cart.php">
                                        <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($item['cart_id']); ?>">
                                        <button type="submit" class="btn-area" <?php echo $has_active_order ? 'disabled' : ''; ?>>🗑️Remove</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <!-- Display message if cart is empty -->
                    <p style="margin-top:30px; font-size:20px;">Your cart is empty!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Checkout Section -->
        <div class="checkout">
            <h2>Checkout Information</h2>
            <?php if ($has_active_order) : ?>
                <!-- Disable checkout if user has an active order -->
                <p class="active-order-message">You cannot place a new order until your current order is completed or cancelled.</p>
            <?php elseif (!empty($cart_items)) : ?>
                <!-- Allow checkout if no active orders exist -->
                <form method="POST" action="place-order.php">
                    <label for="phone-number">Phone Number</label>
                    <input style="margin-top: 10px;" type="text" id="phone-number" name="phone_number" placeholder="Enter your phone number" required>

                    <label for="room-number">Room Number</label>
                    <input type="text" id="room-number" name="room_number" placeholder="Enter your room number" required style="margin-top: 10px;">

                    <button id="checkout-btn" type="submit" <?php echo $has_active_order ? 'disabled' : ''; ?>>Checkout</button>
                </form>
            <?php else : ?>
                <!-- Display message if cart is empty -->
                <p>Your cart is empty! Add items to proceed with checkout.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>