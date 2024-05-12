<?php
include "../cnx.php";
include "../cors.php";

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';


$hashedPassword = md5($password); 

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND pwd = ?");
$stmt->execute(array($username, $hashedPassword));

$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(["status" => true ]); 
} else {
    echo json_encode(["status" => false ]);
}
?>
