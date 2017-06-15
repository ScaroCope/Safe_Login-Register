<?php
include_once 'connect_to_database.php';
$query = "UPDATE users SET Activation =1 WHERE token ='".$_GET['token']."'";
$result = $dbh->query($query);

header ('Location: login_page.php');
?>