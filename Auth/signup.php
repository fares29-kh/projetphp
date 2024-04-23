<?php
include "../cnx.php";
include "../cors.php";

// Extract data from the POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Hash the password securely
$hashedPassword = md5($password); // Use bcrypt or Argon2 for better security

try {
    // Prepare and execute the SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, phone, email, pwd) VALUES (?, ?, ?, ?)");
    $stmt->execute(array($username, $phone, $email, $hashedPassword));

    // Check if the insertion was successful
    $count = $stmt->rowCount();

    // Prepare JSON response based on the result of the insertion
    if ($count > 0) {
        // User inserted successfully
        echo json_encode(["status" => "success"]);
    } else {
        // Failed to insert user
        echo json_encode(["status" => "faild", "error" => "Failed to insert user"]);
    }
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(["status" => "fail", "error" => $e->getMessage()]);
}

?>
