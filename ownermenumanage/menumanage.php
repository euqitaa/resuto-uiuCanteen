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

// Fetch menu items for the logged-in restaurant
$restaurant_name = $_SESSION['restaurant_name'];
$sql = "SELECT food_name, price, food_quantity, food_image, availability FROM restaurants WHERE restaurant_name = ?";
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
    <title>Menu Management - レスト</title>
    <link rel="stylesheet" href="menumanagestyle.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div>
                <a href="" class="branding">レスト</a><br>
                <a href="#" class="company-name"><?php echo htmlspecialchars($restaurant_name); ?></a>
            </div>

            <div class="sidebar-buttons">
                <button class="sidebar-btn"><a href="owner-orders.html" style="text-decoration: none; color: inherit;">Orders</a></button>
                <button class="sidebar-btn"><a href="menumanage.php" style="text-decoration: none; color: inherit;">Menu Management</a></button>
                <button class="sidebar-btn"><a href="history.html" style="text-decoration: none; color: inherit;">History</a></button>
                <button class="sidebar-btn"><a href="make-items.php" style="text-decoration: none; color: inherit;">➕ Add Items</a></button>
            </div>
            <div class="sidebar-support">
                <button class="support-btn">Support</button>
            </div>
        </div>

        <div class="content">
            <h1 style="opacity: 0.6; color: #1b1f46; padding-top: 1rem; padding-left: 1rem;">Menu Management</h1>
            <div class="foodlistparent">
                <ul class="foodlist">
                    <?php
                    // Loop through each food item
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <li>
                                <div class="box">
                                    <img src="<?php echo htmlspecialchars($row['food_image']); ?>" alt="<?php echo htmlspecialchars($row['food_name']); ?>">
                                    <div class="content">
                                        <h3><?php echo htmlspecialchars($row['food_name']); ?></h3>
                                        <h4><?php echo htmlspecialchars($row['price']); ?> Tk</h4>
                                        <div>
                                            <div class="quantityselect">
                                                <div class="quantity">
                                                    <button class="minus" aria-label="Decrease">&minus;</button>
                                                    <input type="number" class="input-box" value="<?php echo htmlspecialchars($row['food_quantity']); ?>" min="0">
                                                    <button class="plus" aria-label="Increase">&plus;</button>
                                                </div>
                                            </div>
                                            <div class="mng-btn-right">
                                                <button class="mng-btn"><?php echo $row['availability'] === 'Available' ? '🚫Unavailable' : '☑️Available'; ?></button>
                                                <button class="mng-btn">☑️Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                    <?php
                        }
                    } else {
                        echo "<p>No menu items found.</p>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>