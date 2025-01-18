<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: login.html?error=unauthorized");
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

// Fetch all riders (username and phone number)
$sql = "SELECT id, username, phone_number FROM riders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Riders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            text-align: center;
            padding-top: 50px;
        }

        h2 {
            color: #1b1f46;
            margin-bottom: 20px;
        }

        table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1b1f46;
            color: white;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .delete-btn {
            padding: 5px 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a.back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Rider Management</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Phone Number</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($rider = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$rider['id']}</td>
                        <td>".htmlspecialchars($rider['username'])."</td>
                        <td>".htmlspecialchars($rider['phone_number'])."</td>
                        <td>
                            <form method='POST' action='delete-rider.php' onsubmit='return confirm(\"Are you sure you want to delete this rider?\");'>
                                <input type='hidden' name='rider_id' value='{$rider['id']}'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No riders found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <a href="admin.php" class="back-btn">← Back to Dashboard</a>

</body>
</html>
