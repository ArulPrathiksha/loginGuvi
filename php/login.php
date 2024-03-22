<?php

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'pratheek';
$dbName = 'guvi';

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            echo "Login successful";
        } else {
            echo "<h1> Login failed. Invalid username or password.</h1>";
            echo "Invalid password: " . $password;
            echo "Hashed password from database: " . $user['password'];
        }
    } else {
        echo "<h1> Login failed. No user found with that username.</h1>";
    }

    $stmt->close();
}
