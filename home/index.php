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
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <title>Welcome to レスト</title>
    <link rel="stylesheet" href="landing-style.css">

    <style>
        .rest-img:hover{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <section class="nav-top">
        <navbar>
            <ul class="nav-list">
                <li class="nav-logo"> <a class="nav-logo" href="index.php">レスト</a> </li>
                <li class="nav-item"> <a href="">📌Location</a> </li>

                <!-- Check if user is logged in -->
                <?php if ($loggedIn): ?>
                    <li class="nav-item">👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                    <li class="nav-item"> <a href="logout.php">Logout</a> </li>
                <?php else: ?>
                    <li class="nav-item"> <a href="login.html">👤Login</a> </li>
                <?php endif; ?>

                <li class="nav-item"> <a href="..\cart\cart.php">🛒Cart</a></li>
                

            </ul>
        </navbar>
    </section>
    <section class="grid">
        <div class="maindiv">
            <div class="content">
                <div class="content-left">
                    <div class="info">
                        <h2>Welcome to <br><span style="color: #fe4119; text-shadow: 0 0 0.5rem #fe4119;">レスト</span>!<br></h2>
                        <div id="info">Get food delivered to your doorstep.</div><br>
                    </div>
                    <button id="exp-canteen" onclick="window.location.href='restaurants.php'">Explore Canteen</button>

                    
                </div>
                <!-- restaurants-->
                <div class="content-right">
                    <div class="restaurantslist">
                        <a href="http://localhost/resuto-uiuCanteen/user_menu/restaurant-menu.php?restaurant=Khan%27s+Kitchen">
                        <img src="..\resources\khans.png" alt="Khans Kitchen" class="rest-img">
                        
                        </a>
                        <p>Khan's Kitchen</p>
                    </div>
                    <div class="restaurantslist">
                        <a href="http://localhost/resuto-uiuCanteen/user_menu/restaurant-menu.php?restaurant=Olympia+Cafe">
                        <img src="..\resources\olympia.png" alt="Olympia" class="rest-img">
                        
                        </a>
                        <p>Olympia Cafe<p>
                    </div>
                    <div class="restaurantslist">
                        <a href="http://localhost/resuto-uiuCanteen/user_menu/restaurant-menu.php?restaurant=Uiu+Cafe">
                        <img src="..\resources\neptune.png" alt="Neptune" class="rest-img">
                        
                        </a>
                        <p>Neptune</p>
                    </div>
                </div>
    
            </div>
        </div>
    </section>
</body>
</html>
<?php
include '../extend.php';
?>
<?php
include '../footer.php';
?>