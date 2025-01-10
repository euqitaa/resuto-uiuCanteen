<?php
// Start the session
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to レスト</title>
    <link rel="stylesheet" href="landing-style.css">
</head>
<body>
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a href="index.php">レスト</a> </li>
                <li class="nav-item"> <a href="">📌Location</a> </li>

                <!-- Check if user is logged in -->
                <?php if ($loggedIn): ?>
                    <li class="nav-item">👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <?php else: ?>
                    <li class="nav-item"> <a href="login.html">👤Login</a> </li>
                <?php endif; ?>

                <li class="nav-item"> <a href="..\cart\cart.html">🛒Cart</a></li>
            </ul>
        </navbar>
    </section>
    <section class="grid">
        <div class="maindiv">
            <div class="content">
                <div class="content-left">
                    <div class="info">
                        <h2>welcome to <br><span style="color: #fe4119; text-shadow: 0 0 0.5rem #fe4119;">レスト</span></h2>
                    </div>
                    <button id="exp-canteen" onclick="window.location.href='restaurants.php'">Explore Canteen</button>

                    
                </div>
                <!-- restaurants-->
                <div class="content-right">
                    <div class="restaurantslist">
                        <img src="..\resources\khans.png" alt="Khans Kitchen">
                        <p>Khans Kitchen</p>
                    </div>
                    <div class="restaurantslist">
                        <img src="..\resources\olympia.png" alt="Olympia">
                        <p>Olympia</p>
                    </div>
                    <div class="restaurantslist">
                        <img src="..\resources\neptune.png" alt="Neptune">
                        <p>Neptune</p>
                    </div>
                </div>
    
            </div>
        </div>
    </section>
</body>
</html>
