<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;

require_once("controller/require.php");

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
		case "login":
			require_once("controller/login.php");
			break;
		case "EULA":
			echo "eula";
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

require_once("view/" . $page . ".php");

?>