<?php
include "../cnx.php";

$username = filterRequest("username");
$password = md5(filterRequest("password"));

$stmt = $conn->prepare("SELECT * from  users WHERE username= ? AND pwd=  ?");
$stmt->execute(array($username, $password));
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "$count"));
} else {
    echo json_encode(array("status" => "fail"));
}
?>
