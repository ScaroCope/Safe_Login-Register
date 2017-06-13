<?php
session_start();
include_once 'connect_to_database.php';
/*$cook = "SELECT userName FROM users";

for ($i=1; $i <= count($cook) ; $i++){*/
if (isset($_SESSION['user'])) {
  header("Location: home.php");
 }

 $error = false;
 
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
	//setcookie($res['userId'], $res['userName']);
	$_SESSION['user'] = $res['userId'];
    header("Location: home.php");
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
<input <?=$red_outline?> type="text" placeholder="Username" name="username"><p></p>
<input <?=$red_outline?> type="password" placeholder="Password" name="password"><p></p>
<button type="submit" name="submit"> Sign-In </button>
</form>
</body>

<a href="register_page.php"> If you're not already registered click here. </a>
</html>