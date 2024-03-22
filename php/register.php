

<?php

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

require '../assets/mongoDriver/vendor/autoload.php';

// MongoDB configuration
$mongoHost = 'localhost'; // MongoDB host
$mongoPort = '27017'; // MongoDB port
$mongoDbName = 'guvi'; // MongoDB database name

// Create a MongoDB client instance
$mongoClient = new MongoDB\Client("mongodb://$mongoHost:$mongoPort");

// Select your MongoDB database
$mongoDb = $mongoClient->$mongoDbName;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];

    // Check if username already exists in MySQL
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists";
    } else {
        // Insert user details into MySQL
        $stmt = $conn->prepare("INSERT INTO users (username, password, email,age,contact) VALUES (?, ?, ?,?,?)");
        $stmt->bind_param("sssis", $username, $password, $email, $age, $contact);

        if ($stmt->execute()) {
            echo "Registration successful";

            // Insert user details into MongoDB
            $collection = $mongoDb->users; // MongoDB collection name
            $insertResult = $collection->insertOne([
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'age' => $age,
                'contact' => $contact
            ]);

            if ($insertResult->getInsertedCount() === 1) {
                echo "User details inserted into MongoDB";
            } else {
                echo "Failed to insert user details into MongoDB";
            }
        } else {
            echo "Registration failed";
        }
    }

    $stmt->close();
}

?>
