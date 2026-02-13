<?php
include "config.php";

if(!isset($_SESSION['user'])) die();

$newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si",$newpass,$_SESSION['user']);
$stmt->execute();

header("Location: ../konto.php");
?>
