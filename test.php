<?php
include "cnx.php";
$req = $conn->query("SELECT * FROM users");
$req->execute();
$users =$req->fetchAll(pdo::FETCH_ASSOC);
print_r($users);?>