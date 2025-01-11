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

// Fetch cart items
$sql = "SELECT c.id AS cart_id, c.food_name, c.quantity, c.price_per_unit, c.total_price, r.food_image 
        FROM cart c 
        INNER JOIN restaurants r 
        ON c.restaurant_name = r.restaurant_name AND c.food_name = r.food_name 
        WHERE c.username = ? AND c.status = 'Pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
	$cart_items[] = $row;
}

$stmt->close();
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
		.checkout {
			width: 30%;
			/* Adjusted width for better alignment */
			border-radius: 1rem;
			background: #fff;
			/* Consistent white background */
			box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
			/* Match other boxes */
			padding: 1.5rem;
			/* Uniform padding */
			margin-left: 20px;
			/* Slight margin to balance spacing */
			align-self: flex-start;
			/* Align to the top with other sections */
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
			/* Removed colored background for a clean look */
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
			/* Subtle background for inputs */
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
			/* Highlight effect on hover */
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
				<div class="shop">
					<?php if (!empty($cart_items)) : ?>
						<?php foreach ($cart_items as $item) : ?>
							<div class="box">
								<img src="<?php echo htmlspecialchars($item['food_image']); ?>" alt="Food Image">
								<div class="content">
									<h3><?php echo htmlspecialchars($item['food_name']); ?></h3>
									<h4><?php echo htmlspecialchars($item['price_per_unit']); ?> Tk</h4>
									<p class="unit">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
									<form method="POST" action="remove-from-cart.php">
										<input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($item['cart_id']); ?>">
										<button type="submit" class="btn-area">🗑️Remove</button>
									</form>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<p>Your cart is empty!</p>
					<?php endif; ?>
				</div>
				<div class="right-bar">
					<?php
					$subtotal = array_sum(array_column($cart_items, 'total_price'));
					$shipping = 30; // Fixed shipping cost
					$total = $subtotal + $shipping;
					?>
					<p><span>Subtotal</span> <span><?php echo $subtotal; ?> Tk</span></p>
					<p><span>Delivery Charge</span> <span><?php echo $shipping; ?> Tk</span></p>
					<hr>
					<p><span>Total</span> <span><?php echo $total; ?> Tk</span></p>
				</div>
			</div>
		</div>
		<div class="checkout">
			<h2>Checkout Information</h2>
			<?php if (!empty($cart_items)) : ?>
				<form method="POST" action="place-order.php">
					<label for="phone-number">Phone Number</label>
					<input style="margin-top: 10px;" type="text" id="phone-number" name="phone_number" placeholder="Enter your phone number" required>

					<label for="room-number">Room Number</label>
					<input type="text" id="room-number" name="room_number" placeholder="Enter your room number" required style="margin-top: 10px;">

					<button id="checkout-btn" type="submit">Checkout</button>
				</form>
			<?php else : ?>
				<p>Your cart is empty! Add items to proceed with checkout.</p>
			<?php endif; ?>
		</div>


	</div>
</body>

</html>