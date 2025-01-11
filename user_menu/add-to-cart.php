<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ..\home\login.html");
    exit();
}

// Check if food_name and restaurant_name are provided in GET request
if (!isset($_GET['food_name']) || !isset($_GET['restaurant_name'])) {
    header("Location: ..\home\restaurants.php");
    exit();
}

$food_name = urldecode($_GET['food_name']);
$restaurant_name = urldecode($_GET['restaurant_name']);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch food details
$sql = "SELECT food_name, food_image, price, food_quantity 
        FROM restaurants 
        WHERE restaurant_name = ? AND food_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $restaurant_name, $food_name);
$stmt->execute();
$result = $stmt->get_result();
$food = $result->fetch_assoc();

// If food not found, redirect back
if (!$food) {
    $stmt->close();
    $conn->close();
    header("Location: ..\home\restaurants.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_quantity = (int)$_POST['quantity'];

    // Ensure quantity is valid
    if ($selected_quantity <= 0 || $selected_quantity > $food['food_quantity']) {
        $error = "Invalid quantity selected!";
    } else {
        // Insert into cart table
        $username = $_SESSION['username'];
        $price = $food['price'];
        $total_price = $price * $selected_quantity;

        $insert_sql = "INSERT INTO cart (username, restaurant_name, food_name, quantity, price, total_price)
                       VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssiii", $username, $restaurant_name, $food_name, $selected_quantity, $price, $total_price);

        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            $stmt->close();
            $conn->close();
            header("Location: restaurant-menu.php?restaurant=" . urlencode($restaurant_name));
            exit();
        } else {
            $error = "Failed to add to cart. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart - レスト</title>
    <link rel="stylesheet" href="add-to-cart.css">
</head>
<body>
    <div class="container">
        <h1>Add to Cart</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <div class="food-details">
            <img src="<?php echo htmlspecialchars($food['food_image']); ?>" alt="<?php echo htmlspecialchars($food['food_name']); ?>">
            <h2><?php echo htmlspecialchars($food['food_name']); ?></h2>
            <p>Price: <?php echo htmlspecialchars($food['price']); ?> Tk</p>
            <p>Available Quantity: <?php echo htmlspecialchars($food['food_quantity']); ?></p>
        </div>
        <form action="" method="POST">
            <label for="quantity">Select Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo htmlspecialchars($food['food_quantity']); ?>" required>
            <div class="form-buttons">
                <button type="submit">Save</button>
                <a class="cancel-button" href="restaurant-menu.php?restaurant=<?php echo urlencode($restaurant_name); ?>">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
