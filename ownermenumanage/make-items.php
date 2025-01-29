<?php
// Start session
session_start();

// Redirect if not logged in
if (!isset($_SESSION['restaurant_name'])) {
    header("Location: ../onwnerdash/owner-login.html");
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

$message = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = $_SESSION['restaurant_name'];
    $food_name = $_POST['food_name'];
    $food_quantity = $_POST['food_quantity'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $food_category = $_POST['food_category'];

    // Handle file upload for food image
    $target_dir = "../resources/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["food_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is valid
    if (isset($_FILES["food_image"]) && $_FILES["food_image"]["error"] === UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES["food_image"]["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
            $message = "File is not an image.";
        }

        // Check file size (5MB max)
        if ($_FILES["food_image"]["size"] > 5000000) {
            $uploadOk = 0;
            $message = "File size exceeds 5MB.";
        }

        // Allow only certain formats
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            $uploadOk = 0;
            $message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        if ($uploadOk === 1) {
            if (move_uploaded_file($_FILES["food_image"]["tmp_name"], $target_file)) {
                // Insert data into the database
                $sql = "INSERT INTO restaurants (restaurant_name, food_name, food_quantity, food_image, price, availability, food_category)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssissss", $restaurant_name, $food_name, $food_quantity, $target_file, $price, $availability, $food_category);

                if ($stmt->execute()) {
                    // Show a popup and redirect
                    echo "<script>
                        alert('Item added successfully!');
                        window.location.href = 'menumanage.php';
                        </script>";
                    exit();
                } else {
                    $message = "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $message = "Error uploading the image.";
            }
        }
    } else {
        $message = "No file was uploaded.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Items - レスト</title>
    <link rel="stylesheet" href="make-items-style.css">
</head>

<body>
    <div class="container">
        <h1>Add New Menu Item</h1>
        <?php if (!empty($message)) echo "<p style='color: red;'>$message</p>"; ?>
        <form action="make-items.php" method="POST" enctype="multipart/form-data">
            <label for="food_name">Food Name:</label>
            <input type="text" id="food_name" name="food_name" required>

            <label for="food_quantity">Quantity:</label>
            <input type="number" id="food_quantity" name="food_quantity" min="1" required>

            <label for="price">Price (Tk):</label>
            <input type="number" id="price" name="price" min="1" required>

            <label for="food_image">Food Image:</label>
            <input type="file" id="food_image" name="food_image" accept="image/*" required>

            <label for="availability">Availability:</label>
            <select id="availability" name="availability" required>
                <option value="Available">Available</option>
                <option value="Not Available">Not Available</option>
            </select>

            <label for="food_category">Category:</label>
            <input type="text" id="food_category" name="food_category" required>

            <button type="submit" class="btn">➕ Add Item</button>
        </form>
    </div>
</body>

</html>
