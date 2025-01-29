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
$sql = "SELECT id, username, is_blocked FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            width: 80%;
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

        .delete-btn, .block-btn {
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .block-btn {
            background-color: #007BFF;
            color: white;
        }

        .block-btn:hover {
            background-color: #0056b3;
        }

        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a.back-btn:hover {
            background-color: #218838;
        }
    </style>
    <script>
        async function toggleBlock(userId, currentStatus) {
            try {
                const response = await fetch('toggle-block.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, is_blocked: currentStatus })
                });

                const data = await response.json();

                if (data.success) {
                    location.reload(); // Refresh to show updated status
                } else {
                    alert('Failed to update status: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</head>
<body>

<h2>User Management</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Is Blocked?</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            $blockButtonText = $user['is_blocked'] ? 'Unblock' : 'Block';
            $nextStatus = $user['is_blocked'] ? 0 : 1;

            echo "<tr>
                    <td>{$user['id']}</td>
                    <td>".htmlspecialchars($user['username'])."</td>
                    <td>" . ($user['is_blocked'] ? 'Yes' : 'No') . "</td>
                    <td>
                        <button class='block-btn' onclick=\"toggleBlock({$user['id']}, $nextStatus)\">$blockButtonText</button>
                        <form method='POST' action='delete-user.php' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                            <input type='hidden' name='user_id' value='{$user['id']}'>
                            <button type='submit' class='delete-btn'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No users found.</td></tr>";
    }
    $conn->close();
    ?>
</table>

<a href="admin.php" class="back-btn">← Back to Dashboard</a>

</body>
</html>
