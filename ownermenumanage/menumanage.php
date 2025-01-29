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
$sql = "SELECT id, food_name, price, food_quantity, food_image, availability FROM restaurants WHERE restaurant_name = ?";
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
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .modal-content h3 {
            margin-bottom: 10px;
        }

        .modal-content input,
        .modal-content select,
        .modal-content button {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .close-btn {
            background-color: #fe4119;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div>
                <a href="../ownerdash/owner-dash.php" class="branding">レスト</a><br>
                <a href="../ownerdash/owner-dash.php" class="company-name"><?php echo htmlspecialchars($restaurant_name); ?></a>
            </div>

            <div class="sidebar-buttons">
                <button class="sidebar-btn"><a href="..\ownerorders\ownerorders.php" style="text-decoration: none; color: inherit;">Orders</a></button>
                <button class="sidebar-btn"><a href="..\ownermenumanage\menumanage.php" style="text-decoration: none; color: inherit;">Menu Management</a></button>
                <button class="sidebar-btn"><a href="..\ownerdash\history.php" style="text-decoration: none; color: inherit;">History</a></button>
                <button class="sidebar-btn"><a href="make-items.php" style="text-decoration: none; color: inherit;">➕ Add Items</a></button>
            </div>
            <div class="sidebar-support">
                <button class="support-btn">Support</button>
            </div>
        </div>


        <!-- Main Content -->
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
                                        <div style="margin-top: 10px;">
                                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['food_quantity']); ?></p>
                                            <p><strong>Availability:</strong>
                                                <?php echo $row['availability'] === 'Available' ? '<span style="color: green">Available</span>' : '<span style="color: red;">Unavailable</span>'; ?>
                                            </p>
                                        </div>
                                        <button type="button" class="open-modal-btn"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['food_name']); ?>"
                                            data-price="<?php echo htmlspecialchars($row['price']); ?>"
                                            data-quantity="<?php echo htmlspecialchars($row['food_quantity']); ?>"
                                            data-availability="<?php echo htmlspecialchars($row['availability']); ?>">
                                            Edit Item
                                        </button>
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

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <h3 id="modal-item-name">Item Name</h3>
            <form id="modal-form" method="POST" action="save-menu-changes.php">
                <input type="hidden" name="id" id="modal-item-id">
                <label for="modal-item-quantity">Quantity:</label>
                <input type="number" name="food_quantity" id="modal-item-quantity" min="0">

                <label for="modal-item-availability">Availability:</label>
                <select name="availability" id="modal-item-availability">
                    <option value="Available">Available</option>
                    <option value="Not Available">Not Available</option>
                </select>

                <button type="submit">Save Changes</button>
                <button type="button" class="close-btn" id="close-modal">Cancel</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const modal = document.getElementById('modal');
        const modalItemName = document.getElementById('modal-item-name');
        const modalItemId = document.getElementById('modal-item-id');
        const modalItemQuantity = document.getElementById('modal-item-quantity');
        const modalItemAvailability = document.getElementById('modal-item-availability');
        const closeModal = document.getElementById('close-modal');

        document.querySelectorAll('.open-modal-btn').forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalItemName.textContent = button.getAttribute('data-name');
                modalItemId.value = button.getAttribute('data-id');
                modalItemQuantity.value = button.getAttribute('data-quantity');
                modalItemAvailability.value = button.getAttribute('data-availability');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.onclick = event => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>