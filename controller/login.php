<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\connection as connection;

require_once("model/connection.php");

session_start();

// New connection
$connection = new Connection('localhost', 'root', '', 'absence');

// When user tries to login
if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$query = "SELECT * FROM gebruikers WHERE email='$email'";
	$result = $connection->query($query);
		
		// Check if passwords match
		if ($password == $result["wachtwoord"]) {
			$_SESSION['email'] = $email;
			
			require_once("view/myAccount.php");
		} else {
			require_once("view/ken.php");
			echo "Wrong username or password!";
		} 

} else {
	require_once("view/ken.php");
}


?>