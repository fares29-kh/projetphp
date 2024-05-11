<?php
include "../cnx.php";
include "../cors.php";


$username = isset($_POST['username']) ? $_POST['username'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$hashedPassword = md5($password); 

try {
    $stmt = $conn->prepare("INSERT INTO users (username, phone, email, pwd) VALUES (?, ?, ?, ?)");
    $stmt->execute(array($username, $phone, $email, $hashedPassword));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "failed", "error" => "Failed to insert user"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "failed", "error" => $e->getMessage()]);
}
?>
