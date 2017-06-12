<?php
session_start();
include_once 'connect_to_database.php';
include "phpmailer/PHPMailerAutoload.php";

if (isset($_POST['submit'])){
	$error = false;
	
	$password = trim ($_POST['password']);
	$password2 = trim ($_POST['password2']);
	$email = trim ($_POST['email']);
	$username = trim ($_POST['username']);
	
	if (strlen($username) < 3 ){
		$rosso1 = true;
		$error = true;
	}
	
	if (strlen($password) < 5){
		$rosso2 = true;
		$rosso3 = true;
		$error = true;
	}
	if ($password != $password2){
		$rosso3 = true;
		$error = true;
	}
	if(!$error){
	$sql_name = $dbh->prepare("SELECT userName FROM users WHERE userName = :contatto_username");
	$sql_name->bindParam(':contatto_username', $username, PDO::PARAM_STR);
	$sql_name->execute();
	if($sql_name->rowCount() > 0){
	$error = true;
	$rosso1 = true;
	}
	}
	 if(!$error){
	$sql_mail = $dbh->prepare("SELECT userEmail FROM users WHERE email = :contatto_email");
	$sql_mail->bindParam(':contatto_email', $email, PDO::PARAM_STR);
	$sql_mail->execute();
	
	if($sql_mail->rowCount() > 0){
	$error = true;
    $rosso4 = true;
	}
	}
	
	$password_cripted = hash ('sha256', $password);
	
	if (!$error){
		require 'email.php';
		setcookie('username', $username, time()+60*60*24*365, "/", "localhost", false);
		setcookie('password', $password_cripted, time()+60*60*24*365, "/", "localhost", false);
		setcookie('email', $email, time()+60*60*24*365, "/", "localhost", false);
	}
	
	if ($rosso1 == true){
		$red_outline1 = 'style="outline: #ff0000 solid"';
		echo "Username must have more than 3 characters, if it's so this username has already been taken. <br>";
	}
	if ($rosso2 == true){
		$red_outline2 = 'style="outline: #ff0000 solid"';
		echo "The password must have more than 5 characters! <br> ";
	}
	if ($rosso3 == true){
		$red_outline3 = 'style="outline: #ff0000 solid"';
		echo "The second password is different from the first. <br>";
	}
	if ($rosso4 == true){
		$red_outline4 = 'style="outline: #ff0000 solid"';
		echo "This email already exists.";
	}

}
?>

<!DOCTYPE html>
<html>

<head>
<title> Register Page </title>
</head>

<body>
<form method="post" action="<?php $SERVER['PHPSELF']; ?>"><p></p>
<input <?=$red_outline1?> type="text" placeholder="Username" name="username"><p></p>
<input <?=$red_outline2?> type="password" placeholder="Password" name="password"><p></p>
<input <?=$red_outline3?> type="password" placeholder="Repeat Password" name="password2"><p></p>
<input <?=$red_outline4?> type="email" placeholder="Email" name="email">
<p></p><button type="submit" name="submiset"> Sign-Up </button>
</form>

<a href="login_page.php"> If you're already registered click here to login. </a>

</body>

</html>