<?php
$conn = new mysqli("localhost","root","","racepedia");
if($conn->connect_error){
    die("Błąd DB");
}
session_start();
?>
