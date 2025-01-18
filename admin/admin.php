<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: login.html?error=unauthorized");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f1f1f1;
        }
        .container {
            margin-top: 100px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
        <h2>Admin Panel</h2>
        
        <!-- Buttons to manage Users, Restaurants, and Riders -->
        <a href="manage-users.php" class="btn">Manage Users</a>
        <a href="manage-restaurants.php" class="btn">Manage Restaurants</a>
        <a href="manage-riders.php" class="btn">Manage Riders</a>

        <br><br>
        <a href="admin-logout.php" class="btn" style="background-color: #dc3545;">Logout</a>
    </div>
</body>
</html>
