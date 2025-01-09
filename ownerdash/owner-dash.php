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

// Fetch daily orders and revenue data
$restaurant_name = $_SESSION['restaurant_name'];
$order_data = [];
$revenue_data = [];
$date_labels = [];

$sql = "SELECT DATE(order_date) as order_date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
        FROM orders 
        WHERE restaurant_name = ? 
        GROUP BY DATE(order_date) 
        ORDER BY DATE(order_date) ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $restaurant_name);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $date_labels[] = $row['order_date'];
    $order_data[] = $row['total_orders'];
    $revenue_data[] = $row['total_revenue'];
}

$stmt->close();
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
                    if (isset($_SESSION['restaurant_name'])) {
                        echo htmlspecialchars($_SESSION['restaurant_name']);
                    } else {
                        echo "Restaurant Name Not Set";
                    }
                    ?>
                </a>
            </div>
            <div class="sidebar-buttons">
                <!-- Orders Button -->
                <button class="sidebar-btn">
                    <a href="../ownerorders/ownerorders.html" style="text-decoration: none; color: inherit;">Orders</a>
                </button>

                <!-- Menu Management Button -->
                <button class="sidebar-btn">
                    <a href="../ownermenumanage/menumanage.php" style="text-decoration: none; color: inherit;">Menu Management</a>
                </button>

                <!-- History Button -->
                <button class="sidebar-btn">
                    <a href="history.html" style="text-decoration: none; color: inherit;">History</a>
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
                    <canvas id="ordersChart"></canvas> <!-- Orders graph -->
                </div>
                <div class="revenue-graph">
                    <h2>Revenue Graph</h2>
                    <canvas id="revenueChart"></canvas> <!-- Revenue graph -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get data from PHP
        const dateLabels = <?php echo json_encode($date_labels); ?>;
        const orderData = <?php echo json_encode($order_data); ?>;
        const revenueData = <?php echo json_encode($revenue_data); ?>;

        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'line', // Line chart
            data: {
                labels: dateLabels, // X-axis labels (dates)
                datasets: [{
                    label: 'Daily Orders',
                    data: orderData, // Y-axis data (orders count)
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Orders'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar', // Bar chart
            data: {
                labels: dateLabels, // X-axis labels (dates)
                datasets: [{
                    label: 'Daily Revenue (TK)',
                    data: revenueData, // Y-axis data (revenue)
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Revenue (TK)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
