<?php
$servername = "localhost";
$DBname="projetphp";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$DBname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  include "functions.php";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>