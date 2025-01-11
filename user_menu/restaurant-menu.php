<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ..\home\login.html");
    exit();
}

// Get the restaurant name from the URL
if (!isset($_GET['restaurant'])) {
    header("Location: ..\home\restaurants.php");
    exit();
}

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

// Fetch food items for the selected restaurant
$sql = "SELECT food_name, food_image, price, availability, food_quantity 
        FROM restaurants 
        WHERE restaurant_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $restaurant_name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant_name); ?> - レスト</title>
    <link rel="stylesheet" href="restaurant-menu.css">
</head>

<body>
    <!-- Navbar -->
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a href="..\home\index.php">レスト</a> </li>
                <li class="nav-item"> <a href="">📌Location</a> </li>
                <li class="nav-item">👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li class="nav-item"> <a href="cart.php">🛒Cart</a></li>
            </ul>
        </navbar>
    </section>

    <!-- Restaurant Info -->
    <section class="restaurant-name-parent">
        <div class="res-info">
            <div class="restaurant-logo">
                <!-- Use the first food image as the placeholder logo -->
                <img class="res-logo" style="object-fit: contain; width:300px;```php" src="<?php echo htmlspecialchars($result->fetch_assoc()['food_image'] ?? 'placeholder.jpg'); ?>" alt="Restaurant Logo">
            </div>
            <div class="restaurant-name">
                <h1 id="res-name-text"><?php echo htmlspecialchars($restaurant_name); ?></h1>
                <label class="res-avail">Explore our menu and place your orders!</label>
            </div>
        </div>
    </section>

    <!-- Food Items -->
    <section class="restaurant-items">
        <div class="restaurant-items-list-freq">
            <div>
                <h1 style="font-size: 30px; font-weight: bold; margin-left: 90px">Food Items</h1>
                <p style="font-family: sans-serif; font-weight: lighter; font-size: 18px; margin-left:90px">Choose items to your liking...</p>
            </div>
            <div class="food-items">
                <ul class="fooditemlist">
                    <?php
                    $result->data_seek(0); // Reset the result pointer
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $availability = $row['availability'] === 'Available' ? 'Available' : 'Not Available';
                            ?>
                            <li class="fooditemlistthing">
                                <div class="food-item-button">
                                    <div class="food-item-button-left">
                                        <div class="foodname">
                                            <label><?php echo htmlspecialchars($row['food_name']); ?></label>
                                        </div>
                                        <div class="foodprice">
                                            <label><?php echo htmlspecialchars($row['price']); ?> Tk</label>
                                        </div>
                                    </div>
                                    <div class="food-item-button-right">
                                        <div class="div-foodimage">
                                            <img style="object-fit: contain; object-position:top;" src="<?php echo htmlspecialchars($row['food_image']); ?>" alt="<?php echo htmlspecialchars($row['food_name']); ?>">
                                        </div>
                                        <div class="addfoodbutton">
                                            <?php if ($availability === 'Available'): ?>
                                                <button class="addbutton">➕🛒 Add to Cart</button>
                                            <?php else: ?>
                                                <p style="color: red; font-weight: bold;">Unavailable</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        echo "<p>No food items found for this restaurant.</p>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </ul>
            </div>
        </div>
    </section>
</body>

</html>
