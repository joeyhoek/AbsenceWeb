<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;

require_once("controller/require.php");

if (isset($_POST['reset'])) {
	$email = $_POST['email'];

	$query = "SELECT * FROM users WHERE email='$email'";
	$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
	$result = $connection->query($query);

	if ($email == $result["email"] && $email !== null) {
		echo "staat erin";
		
		echo $email;
	} else {
		echo "staat er niii in";
		}
	echo "<br>" . $email;
}



if (isset($_POST['email']) && isset($_POST["password"])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$query = "SELECT * FROM users WHERE email='$email'";
	$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
	$result = $connection->query($query);
		// Check if passwords match
		if ($password == $result["password"]) {
			$_SESSION['email'] = $email;
			
			require_once("view/myAccount.php");
		} else {
			// If no url go to login page
			$page = "login";
			require_once("controller/qrCode.php");
			echo "Wrong username or password!";
		}

} else if (isset($_GET["url"])) {
	$page = $_GET["url"];
	switch ($page) {
		case "EULA":
			echo "eula";
			break;
		case "forgotPassword":
			$page = "forgotPassword";
			break;
		case "mainOverview":
			require_once("");
			break;
		default:
			echo "not found";
	}
	
	
} else {
	// If no url go to login page
	$page = "login";
	require_once("controller/qrCode.php");
}

header("Content-Type: text/html; charset=utf-8");
require_once("view/" . $page . ".php");

?>