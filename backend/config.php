<?php
$conn = new mysqli("localhost","root","","racepedia");
if($conn->connect_error){
    die("Błąd połączenia z DB");
}
session_start();

function e($string){
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
