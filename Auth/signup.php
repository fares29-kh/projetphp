<?php
include "../cnx.php";

$username = filterRequest("username");
$phone = filterRequest("phone");
$email = filterRequest("email");
$password = md5(filterRequest("password"));

$stmt = $conn->prepare("INSERT INTO users (username, phone, email, pwd) VALUES (?, ?, ?, ?)");
$stmt->execute(array($username, $phone, $email, $password));
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "$count"));
} else {
    echo json_encode(array("status" => "fail"));
}
?>
