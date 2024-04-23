<?php
include "../cnx.php";
include "../cors.php";

// Extract username and password from the POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Hash the password securely
$hashedPassword = md5($password); // Use bcrypt or Argon2 for better security

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND pwd = ?");
$stmt->execute(array($username, $hashedPassword));

$count = $stmt->rowCount();

if ($count > 0) {
    // User exists, login successful
    echo json_encode(["status" => "success"]); 
} else {
    // User not found or invalid credentials
    echo json_encode(["status" => "fail"]);
}
?>
