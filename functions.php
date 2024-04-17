<?php
function filterRequest($requestname){
    if (isset($_POST[$requestname])) {
        return $_POST[$requestname];
    } else {
        return ''; // Return empty string if the request variable is not set
    }
}
?>
