<?php
include_once 'connect_to_database.php';

$query = "INSERT INTO users(userName,userEmail,userPass) VALUES('".$_COOKIE['username']."','".$_COOKIE['email']."','".$_COOKIE['password_cripted']."')";
$result = $dbh->query($query);

header ('Location: login_page.php');
?>