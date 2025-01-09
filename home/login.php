<?php
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted data
    $username = $_POST['username'] ?? 'No username provided';
    $password = $_POST['password'] ?? 'No password provided';

    // Display the data (for testing purposes)
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
} else {
    // If no form submission, display this message
    echo "Form not submitted!";
}
?>
