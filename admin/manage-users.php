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

// Fetch all users
$sql = "SELECT id, username FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
        .delete-btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">User Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $user['id'] . "</td>
                        <td>" . htmlspecialchars($user['username']) . "</td>
                        <td>
                            <form method='POST' action='delete-user.php'>
                                <input type='hidden' name='user_id' value='" . $user['id'] . "'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No users found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <div style="text-align:center;">
        <a href="admin.php">← Back to Dashboard</a>
    </div>
</body>
</html>
