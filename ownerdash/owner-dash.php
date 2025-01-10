<?php
// Start the session
session_start();

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['owner_username']) || !isset($_SESSION['restaurant_name'])) {
    header("Location: owner-login.html");
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

$restaurant_name = $_SESSION['restaurant_name'];

// Fetch all-time total completed orders
$sql_all_time_orders = "SELECT COUNT(*) as total_completed_orders FROM orders 
                        WHERE restaurant_name = ? AND status = 'Completed'";
$stmt_all_time_orders = $conn->prepare($sql_all_time_orders);
$stmt_all_time_orders->bind_param("s", $restaurant_name);
$stmt_all_time_orders->execute();
$result_all_time_orders = $stmt_all_time_orders->get_result();
$total_completed_orders = $result_all_time_orders->fetch_assoc()['total_completed_orders'] ?? 0;

// Fetch all-time total revenue
$sql_all_time_revenue = "SELECT SUM(total_price) as total_revenue FROM orders 
                         WHERE restaurant_name = ? AND status = 'Completed'";
$stmt_all_time_revenue = $conn->prepare($sql_all_time_revenue);
$stmt_all_time_revenue->bind_param("s", $restaurant_name);
$stmt_all_time_revenue->execute();
$result_all_time_revenue = $stmt_all_time_revenue->get_result();
$total_revenue = $result_all_time_revenue->fetch_assoc()['total_revenue'] ?? 0;

// Fetch data for the graphs (daily orders and revenue)
$order_data = [];
$revenue_data = [];
$date_labels = [];

$sql_graph = "SELECT DATE(order_date) as order_date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
              FROM orders 
              WHERE restaurant_name = ? 
              GROUP BY DATE(order_date) 
              ORDER BY DATE(order_date) ASC";
$stmt_graph = $conn->prepare($sql_graph);
$stmt_graph->bind_param("s", $restaurant_name);
$stmt_graph->execute();
$result_graph = $stmt_graph->get_result();

while ($row = $result_graph->fetch_assoc()) {
    $date_labels[] = $row['order_date'];
    $order_data[] = $row['total_orders'];
    $revenue_data[] = $row['total_revenue'];
}

$stmt_all_time_orders->close();
$stmt_all_time_revenue->close();
$stmt_graph->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard - レスト</title>
    <link rel="stylesheet" href="owner-dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js Library -->
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div>
                <a href="owner-dash.php" class="branding">レスト</a><br>
                <a href="owner-dash.php" class="company-name">
                    <?php
                    // Display restaurant name
                    echo htmlspecialchars($restaurant_name);
                    ?>
                </a>
            </div>
            <div class="sidebar-buttons">
                <!-- Orders Button -->
                <button class="sidebar-btn">
                    <a href="../ownerorders/ownerorders.php" style="text-decoration: none; color: inherit;">Orders</a>
                </button>

                <!-- Menu Management Button -->
                <button class="sidebar-btn">
                    <a href="../ownermenumanage/menumanage.php" style="text-decoration: none; color: inherit;">Menu Management</a>
                </button>

                <!-- History Button -->
                <button class="sidebar-btn">
                    <a href="history.php" style="text-decoration: none; color: inherit;">History</a>
                </button>

                <!-- Logout Button -->
                <button class="sidebar-btn">
                    <a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a>
                </button>
            </div>

            <div class="sidebar-support">
                <button class="support-btn">Support</button>
            </div>
        </div>

        <div class="content">
            <h2 style="padding-top: 1rem; padding-left: 1rem; opacity: 0.6;">Business Summary</h2>
            <div class="dashboard-top">
                <!-- All-Time Total Orders -->
                <div class="total-orders">
                    <h2>Orders</h2>
                    <p class="order-count"><?php echo htmlspecialchars($total_completed_orders); ?></p>
                </div>
                
                <!-- All-Time Total Revenue -->
                <div class="total-revenue">
                    <h2>Revenue</h2>
                    <p class="sales-count"><span style="opacity: 0.8;">TK</span> <?php echo htmlspecialchars($total_revenue); ?></p>
                </div>
            </div>
            <div class="dashboard-bottom">
                <div class="orders-graph">
                    <h2 style="margin:20px 0px 10px 20px;">Orders Graph</h2>
                    <canvas id="ordersChart"></canvas> <!-- Orders graph -->
                </div>
                <div class="revenue-graph">
                    <h2 style="margin:20px 0px 10px 20px;">Revenue Graph</h2>
                    <canvas id="revenueChart"></canvas> <!-- Revenue graph -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Graph data
        const dateLabels = <?php echo json_encode($date_labels); ?>;
        const orderData = <?php echo json_encode($order_data); ?>;
        const revenueData = <?php echo json_encode($revenue_data); ?>;

        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Daily Orders',
                    data: orderData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Orders' }, beginAtZero: true }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Daily Revenue (TK)',
                    data: revenueData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Revenue (TK)' }, beginAtZero: true }
                }
            }
        });
    </script>
</body>

</html>
