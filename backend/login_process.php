<?php
include "config.php";


$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s",$username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user && password_verify($password,$user['password'])){
  $_SESSION['user'] = $user['id'];
  $_SESSION['role'] = $user['role'];
  header("Location: ../index.php");
}else{
  echo "Błędne dane.";
}
?>
