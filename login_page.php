<?php
session_start();
include_once 'connect_to_database.php';

if (isset($_COOKIE['token'])){
	$active = "SELECT Activation FROM users WHERE token ='".$_COOKIE['token']."'";
	if ($active == 1){
	header("Location: home.php");}else {echo "Confirm ur email first!<br>";}
}
 
if( isset($_POST['submit']) ) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$password_cripted = hash ('sha256', $password);
   
	$sql = $dbh->prepare("SELECT userId, userName, userPass FROM users WHERE userName = :contatto_username AND userPass = :contatto_password");
	$sql->bindParam(':contatto_username', $username, PDO::PARAM_STR);
	$sql->bindParam(':contatto_password', $password_cripted, PDO::PARAM_STR);
	$sql->execute();
	$res = $sql->fetchAll();
	
	if( $sql->rowCount() > 0) {
		$token = "SELECT token FROM users WHERE userId='".$res[0]['userId']."'";
		setcookie('token', $token, time()+60*60*24*365, "/", "localhost", false);
		$active = "SELECT Activation FROM users WHERE token ='".$_COOKIE['token']."'";
		if ($active == 1){
			header("Location: home.php");}
	} else {
		echo "Incorrect credentials, try again!";
		$error = true;
   }
   
   if ($error == true){
	   $red_outline = 'style="outline: #ff0000 solid"';
   }
}
?>

<!DOCTYPE html>
<html>

<head>
<title> Login Page </title>
</head>

<body>
<form method="post" action="<?php $SERVER['PHPSELF']; ?>"><p></p>
<input <?=$red_outline?> type="text" placeholder="Username" id="username" name="username"><p></p>
<input <?=$red_outline?> type="password" placeholder="Password" id="password" name="password"><p></p>
<input type="button" id="submitset" name="submit" onclick="verify()" value="Sign-In">
</form>

<a href="register_page.php"> If you're not already registered click here. </a>

<script>
function verify(){
	var error = false;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var rosso1 = false, rosso2 = false;
	
	if (username.length < 4 || username.length > 12 || /\s/.test(username) ){
		error = true;
		rosso1 = true;
	}
	if (password.length < 5 || /\s/.test(password)){
		error = true;
		rosso2 = true;
	}

if (rosso1 == true){
	document.getElementById('username').setAttribute('style',"outline: #ff0000 solid");
	window.alert("Invalid username.");
} else {document.getElementById('username').removeAttribute('style',"outline: #ff0000 solid");}
if (rosso2 == true){
	document.getElementById('password').setAttribute('style', "outline: #ff0000 solid");
	window.alert("Invalid password.");
} else {document.getElementById('password').removeAttribute('style',"outline: #ff0000 solid");}
if (error == false){
	document.getElementById('submitset').setAttribute('type', 'submit');
	document.getElementById('submitset').click();
}
}
</script>

</body>

</html>