<?php 
$messaggio = new PHPMailer;
$messaggio->IsSMTP();
$messaggio->IsHTML(true);
$messaggio->Host='smtp.gmail.com';
$messaggio->SMTPAuth = true;
$messaggio->Username = 'mvaccari05@gmail.com';
$messaggio->Password = 'mattiavaccari';
$messaggio->SMTPSecure = 'ssl';
$messaggio->Port = 465;
$messaggio->setFrom('mvaccari05@gmail.com');
$messaggio->addAddress($email);
$messaggio->addReplyTo('mvaccari05@gmail.com'); 
$messaggio->Subject='Account Confirmation';
$messaggio->Body = '<a href="confirm.php"> Click here to confirm ur account. </a>';
if (!$messaggio->Send()) {
	echo "Mailer Error: " . $messaggio->ErrorInfo; 
} else {
	echo "Confirm ur account by the email's URL.";  
}  
?>