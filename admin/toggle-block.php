<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-canteen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id']) && isset($data['is_blocked'])) {
    $userId = (int)$data['user_id'];
    $isBlocked = (int)$data['is_blocked'];

    // Update query
    $stmt = $conn->prepare("UPDATE users SET is_blocked = ? WHERE id = ?");
    $stmt->bind_param("ii", $isBlocked, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

$conn->close();
?>
