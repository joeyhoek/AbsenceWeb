<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;
use Team10\Absence\Model\User as User;

session_start();
require_once("controller/require.php");

if (isset($_GET["action"]) && $_GET["action"] ==  "logout") {
	(new Login)->logout();
}

if (isset($_POST['email']) && isset($_POST["password"])) {
	if ((new Login)->checkLogin($_POST["email"], $_POST["password"])) {
		$page = "dashboard";
		$pageTitle = "Dashboard";
		header("Location: /");
	} else {
		$page = "login";
		$pageTitle = "Login";
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
		(new Login)->logout();
		header("Location: /");
	}
	
	$adminHeader = true;
	$user = new User($_SESSION["userId"]);
	$userRole = $user->getRole();
	if (isset($_GET["url"]) && !isset($page)) {
		$page = $_GET["url"];
		
		if ($userRole == 1) {
			// STUDENT
			switch ($page) {
				case "about":
					$page = "about";
					$pageTitle = "About us";
					break;
				case "profile":
					$page = "profile";
					$pageTitle = "Profile";
					break;
				default:
					$page = "404";
					$pageTitle = "404 Page not found";
			}
		} elseif ($userRole == 2) {
			// TEACHER
			switch ($page) {
				case "about":
					$page = "about";
					$pageTitle = "About us";
					break;
				case "profile":
					$page = "profile";
					$pageTitle = "Profile";
					break;
				case "lesson":
					require_once("controller/lesson.php");
					break;
				case "courseOverview":
					$page = "courseOverview";
					$pageTitle = "Course Overview";
					break;
				case "overview";
					$page = "overview";
					$pageTitle = "Overview";
					break;
				case "webClient":
					$page = "webClient";
					break;
				default:
					header("HTTP/1.0 404 Not Found");
					$page = "404";
					$pageTitle = "404 Page not found";
			}
		} elseif ($userRole == 3) {
			// STUDENT COUNSELOR
			switch ($page) {
				case "about":
					$page = "about";
					$pageTitle = "About us";
					break;
				case "profile":
					$page = "profile";
					$pageTitle = "Profile";
					break;
				case "lesson":
					require_once("controller/lesson.php");
					break;
				case "overview";
					$page = "overview";
					$pageTitle = "Overview";
					break;
				case "webClient":
					$page = "webClient";
					break;
				default:
					header("HTTP/1.0 404 Not Found");
					$page = "404";
					$pageTitle = "404 Page not found";
			}
		} elseif ($userRole == 4) {
			// TEAMLEADER
			switch ($page) {
				case "about":
					$page = "about";
					$pageTitle = "About us";
					break;
				case "profile":
					$page = "profile";
					$pageTitle = "Profile";
					break;
				case "lesson":
					require_once("controller/lesson.php");
					break;
				case "manage";
					$page = "manage";
					$pageTitle = "Manage";
					break;
				case "overview";
					$page = "overview";
					$pageTitle = "Overview";
					break;
				case "webClient":
					$page = "webClient";
					break;
				default:
					header("HTTP/1.0 404 Not Found");
					$page = "404";
					$pageTitle = "404 Page not found";
			}
		}
	} else {
		$page = "dashboard";
		$pageTitle = "Dashboard";
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
			$pageTitle = "Recover Password";
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
				if (isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
					(new User)->changePassword($_POST["newPassword"], $_POST["confirmPassword"], $_GET["token"]);
				}
			} else {
				header("HTTP/1.0 404 Not Found");
				$page = "404";
				$pageTitle = "404 Page not found";
			}
			break;
		case "desktopClient":
			$page = "desktopClient";
			break;
		case "mobileClient":
			$page = "mobileClient";
			break;
		default:
			header("HTTP/1.0 404 Not Found");
			$page = "404";
			$pageTitle = "404 Page not found";
	}
} elseif (!isset($page)) {
	// If no url go to login page
	$page = "login";
	$pageTitle = "Login";
}

if ($page !== "desktopClient" && $page !== "mobileClient" && $page !== "webClient") {
	require_once("view/head.php");
	if (isset($adminHeader) && $adminHeader === true && $page !== "404") {
		require_once("view/dashboard.php");
	}
	
	if ($page == "login") {
		require_once("controller/qrCode.php");
	}		
	require_once("view/" . $page . ".php");
	
	if (isset($adminHeader) && $adminHeader === true && $page !== "404") {
		require_once("view/foot.php");
	}
	
	require_once("view/footer.php");
} else {
	require_once("api/" . $page . ".php");
}

session_write_close();

?>