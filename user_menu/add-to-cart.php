<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../home/login.html");
    exit();
}

// Get food and restaurant details from GET parameters
if (!isset($_GET['food_name']) || !isset($_GET['restaurant'])) {
    header("Location: ../home/restaurants.php");
    exit();
}

$food_name = urldecode($_GET['food_name']);
$restaurant_name = urldecode($_GET['restaurant']);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch food details for the selected item
$sql = "SELECT food_name, food_image, price, food_quantity 
        FROM restaurants 
        WHERE restaurant_name = ? AND food_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $restaurant_name, $food_name);
$stmt->execute();
$result = $stmt->get_result();
$food_item = $result->fetch_assoc();

if (!$food_item) {
    header("Location: ../home/restaurants.php");
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <link rel="stylesheet" href="add-to-cart.css">
</head>

<body>
    <div class="container">
        <h1>Add to Cart</h1>
        <form action="save-to-cart.php" method="POST">
            <label for="food_name">Food Name:</label>
            <input type="text" id="food_name" name="food_name" value="<?php echo htmlspecialchars($food_item['food_name']); ?>" readonly>

            <img src="<?php echo htmlspecialchars($food_item['food_image']); ?>" alt="Food Image" style="width: 100%; max-height: 200px; object-fit: contain; margin-bottom: 15px;">

            <label for="price">Price (per unit):</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($food_item['price']); ?> Tk" readonly>

            <label for="available_quantity">Available Quantity:</label>
            <input type="text" id="available_quantity" name="available_quantity" value="<?php echo htmlspecialchars($food_item['food_quantity']); ?>" readonly>

            <label for="quantity">Select Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo htmlspecialchars($food_item['food_quantity']); ?>" required>

            <input type="hidden" name="restaurant_name" value="<?php echo htmlspecialchars($restaurant_name); ?>">

            <button type="submit" class="btn">Save to Cart</button>
            <a href="restaurant-menu.php?restaurant=<?php echo urlencode($restaurant_name); ?>" class="btn cancel">Cancel</a>
        </form>
    </div>
</body>

</html>
