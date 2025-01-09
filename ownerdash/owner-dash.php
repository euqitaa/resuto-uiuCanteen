<?php
// Start the session
session_start();

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['owner_username']) || !isset($_SESSION['restaurant_name'])) {
    // Redirect to the login page if not logged in
    header("Location: owner-login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard - レスト</title>
    <link rel="stylesheet" href="owner-dashboard.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div>
                <a href="" class="branding">レスト</a><br>
                <a href="#" class="company-name">
                    <?php
                    // Display restaurant name
                    if (isset($_SESSION['restaurant_name'])) {
                        echo $_SESSION['restaurant_name'];
                    } else {
                        echo "Restaurant Name Not Set";
                    }
                    ?>
                </a>
            </div>

            <div class="sidebar-buttons">
                <button class="sidebar-btn">Orders</button>
                <button class="sidebar-btn">Menu Management</button>
                <button class="sidebar-btn">History</button>
                <button class="sidebar-btn"><a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a></button>
            </div>
            <div class="sidebar-support">
                <button class="support-btn">Support</button>
            </div>
        </div>

        <div class="content">
            <h2 style="padding-top: 1rem; padding-left: 1rem; opacity: 0.6;">Business Summary</h2>
            <div class="dashboard-top">
                <div class="total-orders">
                    <h2>Orders</h2>
                    <p class="order-count">0</p>
                </div>
                <div class="total-revenue">
                    <h2>Revenue</h2>
                    <p class="sales-count"><span style="opacity: 0.8;">TK</span> 0</p>
                </div>
            </div>
            <div class="dashboard-bottom">
               <div class="orders-graph">
                <h2>Orders Graph</h2>
               </div>
               <div class="revenue-graph">
                <h2>Revenue Graph</h2>
               </div>
            </div>
        </div>
    </div>
</body>

</html>
