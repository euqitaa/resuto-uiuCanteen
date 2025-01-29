<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
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

// Fetch unique restaurant names with a random food image for each
$sql = "SELECT restaurant_name, 
               (SELECT food_image 
                FROM restaurants r2 
                WHERE r2.restaurant_name = r1.restaurant_name 
                ORDER BY RAND() LIMIT 1) AS random_image 
        FROM restaurants r1 
        GROUP BY restaurant_name";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Restaurants - レスト</title>
    <link rel="stylesheet" href="restaurants-style.css">
    <link rel="stylesheet" href="landing-style.css">
</head>

<body>
    <!-- Navbar -->
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a class="nav-logo" href="index.php">レスト</a> </li>
                <li class="nav-item"> <a href="">📌Location</a> </li>
                <li class="nav-item">👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li class="nav-item"> <a href="..\cart\cart.html">🛒Cart</a></li>
            </ul>
        </navbar>
    </section>

    <!-- Main Content -->
    <section class="restaurants-page">
        <h1>Explore Our Restaurants</h1>
        <div class="restaurants-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = htmlspecialchars($row['random_image']);
                    ?>
                    <div class="restaurant-card">
                        <a href="..\user_menu\restaurant-menu.php?restaurant=<?php echo urlencode($row['restaurant_name']); ?>">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['restaurant_name']); ?>">
                            <h2><?php echo htmlspecialchars($row['restaurant_name']); ?></h2>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No restaurants found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
</body>

</html>
