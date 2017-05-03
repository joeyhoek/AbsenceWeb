<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;

require_once("controller/require.php");

if (isset($_POST['email']) && isset($_POST["password"])) {
	if ((new Login)->checkLogin($_POST["email"], $_POST["password"])) {
		$page = "myAccount";
	} else {
		$page = "login";
	}
} else if (isset($_GET["url"])) {
	$page = $_GET["url"];
	switch ($page) {
		case "EULA":
			echo "eula";
			break;
		case "forgotPassword":
			$page = "forgotPassword";
			if (isset($_POST["email"])) {
				$email = $_POST['email'];
				$token = (new Token)->generateToken();
				$title = "Password Recovery";
				$message = "Click <a href='" . PROTOCOL . DOMAIN . ROOT . "forgotPassword?token=$token'>here</a> to reset your password.";
				$headers = "From: Ken@ken.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				mail($email, $title, $message, $headers);
				echo $email;
			}
			break;
		case "mainOverview":
			require_once("");
			break;
		case "test":
			$page = "test";
			break;
			
			
			case "resetPassword":
			$page = "resetPassword";
			break;
			
			
			
			
		default:
			$page = "404";
	}
	
	
} else {
	// If no url go to login page
	$page = "login";
}

if ($page == "login") {
	require_once("controller/qrCode.php");
}

header("Content-Type: text/html; charset=utf-8");
require_once("view/" . $page . ".php");

?>