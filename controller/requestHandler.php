<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;
use Team10\Absence\Model\User as User;

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
				(new Token)->sendToken($_POST['email']);
			}
			break;
		case "test":
			$page = "test";
			break;
		case "resetPassword":
			if (isset($_GET["token"]) && (new Token)->checkToken($_GET["token"])) {
				$page = "resetPassword";
				echo $_POST["newPassword"] . $_POST["confirmPassword"];
				if (isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
					(new User)->changePassword($_POST["newPassword"], $_POST["confirmPassword"], $_GET["token"]);
					header("Location: /");
				}
			} else {
				$page = "404";
			}
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