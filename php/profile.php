<?php

require '../assets/mongoDriver/vendor/autoload.php';

// MySQL configuration
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'pratheek';
$dbName = 'guvi';

// Create a connection to MySQL database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check MySQL connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// MongoDB configuration
$mongoHost = 'localhost';
$mongoPort = '27017';
$mongoDbName = 'guvi';

// Create a MongoDB client instance
$mongoClient = new MongoDB\Client("mongodb://$mongoHost:$mongoPort");

// Select your MongoDB database
$mongoDb = $mongoClient->$mongoDbName;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile update
    $username = $_POST['username'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];

    echo "username : " . $username . " age : " . $age . " contact : " . $contact;

    // Update user profile in MySQL
    $stmt = $conn->prepare("UPDATE users SET age=?, contact=? WHERE username=?");
    $stmt->bind_param("iss", $age, $contact, $username);

    if ($stmt->execute()) {
        // Update user profile in MongoDB
        $collection = $mongoDb->users;
        echo $collection;
        $updateResult = $collection->updateOne(
            ['username' => $username],
            ['$set' => ['age' => $age, 'contact' => $contact]]
        );
        echo $updateResult->getModifiedCount();

        if ($updateResult->getModifiedCount() > 0) {
            echo "\nProfile updated successfully";
        } else {
            echo "\nFailed to update profile in MongoDB";
        }
    } else {
        echo "Failed to update profile in MySQL";
    }

    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Handle fetching profile data
    $username = $_GET['username'];

    // Fetch user profile from MongoDB
    $collection = $mongoDb->users;
    $userProfile = $collection->findOne(['username' => $username]);

    if ($userProfile) {
        echo json_encode($userProfile);
    } else {
        echo "User profile not found";
    }
}
