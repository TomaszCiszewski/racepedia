<?php
include "config.php";

$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if(!$email || !$username || !$password){
 header("Location: ../register.php?error=Uzupełnij wszystkie pola");
 exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users(username,email,password) VALUES(?,?,?)");
$stmt->bind_param("sss",$username,$email,$hash);

if($stmt->execute()){
 header("Location: ../login.php");
}else{
 header("Location: ../register.php?error=Użytkownik lub email już istnieje");
}
