<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow requests with the GET, POST, and OPTIONS methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow requests with the following headers
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Respond to preflight requests

?>
