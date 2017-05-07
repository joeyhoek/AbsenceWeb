<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;
use Team10\Absence\Model\User as User;

//session_start();
//session_destroy();
session_start();
require_once("controller/require.php");

if (isset($_GET["action"]) && $_GET["action"] ==  "logout") {
	(new Login)->logout();
}

if (isset($_POST['email']) && isset($_POST["password"])) {
	if ((new Login)->checkLogin($_POST["email"], $_POST["password"])) {
		$page = "myAccount";
	} else {
		$page = "login";
	}
} elseif (!isset($_SESSION["userId"])) {
	$result = (new Token)->checkSessionIdAndGive(session_id());

	if ($result !== false):
		$_SESSION["userId"] = $result["userId"];
		$_SESSION["token"] = $result["token"];

		(new Token)->deleteSessionId(session_id());
	endif;
} elseif (isset($_SESSION['userId']) && isset($_SESSION["token"])) {
	if (!(new Token)->verifySessionToken($_SESSION['userId'], $_SESSION["token"], "web")) {
		$page = "login";
	}
	
	if (isset($_GET["url"]) && !isset($page)) {
		$page = $_GET["url"];
		
		$user = new User($_SESSION["userId"]);
		$userRole = $user->getRole();
		if ($userRole == 1) {
			// STUDENT
			switch ($page) {
				default:
					$page = "404";
			}
		} elseif ($userRole == 2) {
			// TEACHER
			switch ($page) {
				default:
					$page = "404";
			}
		} elseif ($userRole == 3) {
			// STUDENT COUNSELOR
			switch ($page) {
				default:
					$page = "404";
			}
		} elseif ($userRole == 4) {
			// TEAMLEADER
			switch ($page) {
				case "uploadCSV";
					$page = "uploadCSV";
					break;
				default:
					$page = "404";
			}
		}
	} else {
		$page = "myAccount";
	}
}
	
if (isset($_GET["url"]) && !isset($page)) {
	$page = $_GET["url"];
	// Without logging in
	switch ($page) {
		case "EULA":
			echo "eula";
			break;
		case "forgotPassword":
			$page = "forgotPassword";
			if (isset($_POST["email"])) {
				$username = $_POST["email"];
				if (strpos($username, "@")):
					$email = $username;
				else:
					if ($username[0] !== "s" && is_numeric($username)):
						$email = "s" . $username . "@student.windesheim.nl";
					elseif ($username[0] == "s"):
						$email = $username . "@student.windesheim.nl";
					else:
						$id = $username . "@docent.windesheim.nl";
					endif;
				endif;

				if ((new Token)->sendToken($email)) {
					header("Location: /");
				}
			}
			break;
		case "resetPassword":
			if (isset($_GET["token"]) && (new Token)->checkToken($_GET["token"])) {
				$page = "resetPassword";
				echo $_POST["newPassword"] . $_POST["confirmPassword"];
				if (isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
					(new User)->changePassword($_POST["newPassword"], $_POST["confirmPassword"], $_GET["token"]);
					(new User)->deleteForgotToken($_GET["token"]);
					header("Location: /");
				}
			} else {
				$page = "404";
			}
			break;
		case "desktopClient":
			$page = "desktopClient";
			break;
		case "mobileClient":
			$page = "mobileClient";
			break;
		default:
			$page = "404";
	}
} elseif (!isset($page)) {
	// If no url go to login page
	$page = "login";
}

if ($page !== "desktopClient" && $page !== "mobileClient") {
	require_once("view/head.php");
	if ($page == "login") {
		require_once("controller/qrCode.php");
	}		
	require_once("view/" . $page . ".php");
	require_once("view/footer.php");
} else {
	require_once("api/" . $page . ".php");
}

session_write_close();

?>