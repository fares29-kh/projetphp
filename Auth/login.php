<?php
include "../cnx.php";
include "../cors.php";

// Check if both username and password are provided
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = filterRequest("username");
    $password = filterRequest("password");

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
        echo json_encode(["status" => "fail", "error" => "Invalid username or password"]);
    }
} else {
    // Username or password not provided in the request
    echo json_encode(["status" => "fail", "error" => "Username or password not provided"]);
}
?>
