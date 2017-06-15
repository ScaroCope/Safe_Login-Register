<?php
	session_start();
	include_once 'connect_to_database.php';
	require 'phpmailer/PHPMailerAutoload.php';
	require 'php-jwt-master/src/JWT.php';

	if (isset($_POST['submit'])){
	
	$password = trim ($_POST['password']);
	$password2 = trim ($_POST['password2']);
	$email = trim ($_POST['email']);
	$username = trim ($_POST['username']);

	$sql_name = $dbh->prepare("SELECT userName FROM users WHERE userName = :contatto_username");
	$sql_name->bindParam(':contatto_username', $username, PDO::PARAM_STR);
	$sql_name->execute();
	if($sql_name->rowCount() > 0){
	$error = true;
	$rosso1 = true;
	}

	$sql_mail = $dbh->prepare("SELECT userEmail FROM users WHERE email = :contatto_email");
	$sql_mail->bindParam(':contatto_email', $email, PDO::PARAM_STR);
	$sql_mail->execute();
	if($sql_mail->rowCount() > 0){
	$error = true;
    $rosso4 = true;
	}
	
	$password_cripted = hash ('sha256', $password);

	if (!$error){
		$token = hash('ripemd160', time()*3 + time()/55);
		require 'email.php';
		setcookie('token', $token, time()+60*60*24*365, "/", "localhost", false);
		$query = "INSERT INTO users(userName,userEmail,userPass, Activation, token) VALUES('".$username."','".$email."','".$password_cripted."', '0', '".$token."')";
		$result = $dbh->query($query);
	}
	if ($rosso1 == true){
		$red_outline1 = 'style="outline: #ff0000 solid"';
		echo "This username has already been taken. <br>";
	}
	if ($rosso4 == true){
		$red_outline4 = 'style="outline: #ff0000 solid"';
		echo "This email already exists.";
	}}
?>
<!DOCTYPE html>
<html>

<head>
<title> Register Page </title>
</head>

<body>
<form id="all" method="post" action="<?php $SERVER['PHPSELF']; ?>"><p></p>
<input <?=$red_outline1?> type="text" placeholder="Username" name="username" id="username"><p></p>
<input type="password" placeholder="Password" name="password" id="password"><p></p>
<input type="password" placeholder="Repeat Password" id="password2"><p></p>
<input <?=$red_outline4?> type="email" placeholder="Email" name="email" id="email"><p></p>
<input type="button" id="submitset" name="submit" onclick="verify()" value="Submit">
</form>

<a href="login_page.php"> If you're already registered click here to login. </a>

<script>
function verify(){
	var error = false;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var password2 = document.getElementById('password2').value;
	var email = document.getElementById('email').value;
	var rosso1 = false, rosso2 = false, rosso3 = false, rosso4 = false;
	
	if (username.length < 4 || username.length > 12 || /\s/.test(username) ){
		error = true;
		rosso1 = true;
	}
	if (username == password){
		error = true;
		rosso1 = true;
		rosso2 = true;
	}
	if (password.length < 5 || /\s/.test(password)){
		error = true;
		rosso2 = true;
		rosso3 = true;
	}
	if (password2 != password){
		error = true;
		rosso3 = true;
	}
	if (email.length == 0){
		error = true;
		rosso4 = true;
	}
	
if (rosso1 == true){
	document.getElementById('username').setAttribute('style',"outline: #ff0000 solid");
	window.alert("The username must have more than 3 characters and less than 13 and it musn't contain spaces.");
} else {document.getElementById('username').removeAttribute('style',"outline: #ff0000 solid");}
if (rosso2 == true){
	document.getElementById('password').setAttribute('style', "outline: #ff0000 solid");
	window.alert("The password must contain more than 4 characters and it musn't contain spaces and be equal to the username.");
} else {document.getElementById('password').removeAttribute('style',"outline: #ff0000 solid");}
if (rosso3 == true){
	document.getElementById('password2').setAttribute('style', "outline: #ff0000 solid");
	window.alert("The second password has to be equal to the first.");
} else {document.getElementById('password2').removeAttribute('style',"outline: #ff0000 solid");}
if (rosso4 == true){
	document.getElementById('email').setAttribute('style', "outline: #ff0000 solid");
	window.alert("The email is empty.");
} else {document.getElementById('email').removeAttribute('style',"outline: #ff0000 solid");}
if (error == false){
	document.getElementById('submitset').setAttribute('type', 'submit');
	document.getElementById('submitset').click();
}
}
</script>

</body>

</html>