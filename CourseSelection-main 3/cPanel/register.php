<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $interested_topics = $_POST['interested_topics'];
    $credits_earned = $_POST['credits_earned'];
    $demographics = $_POST['demographics'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $db = new mysqli('localhost', 'root', '', 'shahk6_coursemanagement');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Prepare the SQL statement and check for errors
    $stmt = $db->prepare("INSERT INTO users (username, password, name, address, interested_topics, credits_earned, demographics, email, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error in preparing statement: " . $db->error);
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bind parameters to the prepared statement
    $bind_result = $stmt->bind_param("sssssisss", $username, $hashed_password, $name, $address, $interested_topics, $credits_earned, $demographics, $email, $phone_number);

    if (!$bind_result) {
        die("Error in binding parameters: " . $stmt->error);
    }

    // Execute the prepared statement
    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    if ($stmt->affected_rows === 1) {
        // Close the statement and database connection
        $stmt->close();
        $db->close();

        // Redirect to login.html with success message
        header("Location: login.html?message=User%20registered%20successfully");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $db->close();
}
?>
