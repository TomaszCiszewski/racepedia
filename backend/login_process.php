<?php
include "config.php";

$username=$_POST['username'];
$password=$_POST['password'];

$res=$conn->query("SELECT * FROM users WHERE username='$username'");
$user=$res->fetch_assoc();

if($user && password_verify($password,$user['password'])){
    $_SESSION['user']=$user['id'];
    header("Location: ../index.php");
}else{
    echo "Błędne dane.";
}
?>
