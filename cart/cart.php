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
		.wrapper {
			display: flex;
			max-width: 90vw;
			margin: 5rem auto;
			border: none;
			box-shadow: 0 0 2rem rgba(0, 0, 0, 0.1);
			padding: 2rem;
			border-radius: 1rem;
			gap: 2rem;
			/* Increased gap for better spacing */
			flex-grow: 1;
			justify-content: flex-start;
			/* Align items to the start */
		}

		.project {
			display: flex;
			flex: 75%;
			/* Occupy 75% of the space for cart items */
			flex-direction: row;
			gap: 2rem;
			/* Space between cart items and right bar */
		}

		.shop {
			flex: 65%;
		}

		.right-bar {
			flex: 35%;
			padding: 20px;
			height: auto;
			border-radius: 1rem;
			background: #fff;
			box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
		}

		.checkout {
			width: 30%;
			border-radius: 1rem;
			position: relative;
			border: none;
			margin-left: auto;
			/* Align to the right side */
			box-shadow: 0 0 2rem rgba(0, 0, 0, 0.1);
			padding: 2rem;
			background: #fff;
		}

		.checkout h2 {
			font-size: 1.5rem;
			font-weight: bold;
			color: #1b1f46;
			margin-bottom: 1rem;
			text-align: left;
			width: 100%;
		}

		.form-group {
			display: flex;
			flex-direction: column;
			width: 100%;
			margin-bottom: 1rem;
		}

		label {
			font-size: 1rem;
			font-weight: bold;
			margin-bottom: 0.5rem;
			color: #1b1f46;
		}

		input[type="text"] {
			width: 100%;
			padding: 10px;
			font-size: 1rem;
			border-radius: 5px;
			border: 1px solid #ccc;
			background-color: #f9f9f9;
			box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
		}

		input[type="text"]:focus {
			border-color: #1b1f46;
			outline: none;
			background-color: #fff;
			box-shadow: 0 0 5px rgba(27, 31, 70, 0.2);
		}

		.checkout-btn {
			width: 100%;
			padding: 15px;
			background-color: #1b1f46;
			color: white;
			border: none;
			border-radius: 5px;
			font-size: 1.1rem;
			font-weight: bold;
			cursor: pointer;
			text-align: center;
			transition: background-color 0.3s ease-in-out;
		}

		.checkout-btn:hover {
			background-color: #fe4119;
			box-shadow: 0 5px 15px rgba(254, 65, 25, 0.4);
		}

		.checkout-btn:disabled {
			background-color: #ddd;
			cursor: not-allowed;
			box-shadow: none;
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

		<div class="checkout-details">
			<h2>Checkout Information</h2>
			<form method="POST" action="checkout.php">
				<div class="form-group">
					<label for="phone_number">Phone Number:</label>
					<input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
				</div>
				<div class="form-group">
					<label for="room_number">Room Number:</label>
					<input type="text" id="room_number" name="room_number" placeholder="Enter your room number" required>
				</div>
				<button type="submit" class="checkout-btn">Checkout</button>
			</form>
		</div>
	</div>
</body>

</html>