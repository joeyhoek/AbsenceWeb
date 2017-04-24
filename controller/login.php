<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\connection as connection;

require_once("model/connection.php");

session_start();

// New connection
$connection = new Connection('localhost', 'root', '', 'absence');

// When user tries to login
if (isset($_POST['email']) && isset($_POST["password"])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$query = "SELECT * FROM users WHERE email='$email'";
	$result = $connection->query($query);
		var_dump($result);
		// Check if passwords match
		if ($password == $result["password"]) {
			$_SESSION['email'] = $email;
			
			require_once("view/myAccount.php");
		} else {
			require_once("view/login.php");
			echo "Wrong username or password!";
		} 

} else {
	require_once("controller/qrCode.php");
	require_once("view/login.php");
}


?>