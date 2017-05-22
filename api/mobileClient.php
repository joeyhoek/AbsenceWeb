<?php

use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;
use Team10\Absence\Model\User as User;

$tokenObj = new Token;

if (isset($_POST["username"]) && isset($_POST["password"])):
	if ((new Login)->checkLogin($_POST["username"], $_POST["password"], false)):
		$username = $_POST["username"];
		if (strpos($username, "@")):
			$id = (new User)->getIdFromEmail($username);
		else:
			if ($username[0] !== "s" && is_numeric($username)):
				$id = "s" . $username;
			else:
				$id = $username;
			endif;
		endif;
		
		if ($tokenObj->checkSessionToken($id, "mobile") !== false):
			$tokenObj->deleteSessionToken($id, "mobile");
		endif;

		$token = $tokenObj->generateToken();
		$tokenObj->addSessionToken($id, $token, "mobile");

		$user = new User($id);

		$result = [
			"userId" => $id,
			"token" => $token,
			"firstname" => $user->getFirstname(),
			"lastname" => $user->getLastname()
		];
		echo json_encode($result);
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["username"]) && isset($_POST["action"]) && $_POST["action"] == "resetPass"):
	$username = $_POST["username"];
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

	if ($tokenObj->sendToken($email)):
		echo 1;
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["userId"]) && isset($_POST["token"]) && isset($_POST["clientId"])):
	$userId = $_POST["userId"]; 
	if ($tokenObj->verifySessionToken($userId, $_POST["token"], "mobile")):
		if ($tokenObj->checkSessionToken($userId, "web") !== false):
			$tokenObj->deleteSessionToken($userId, "web");
		endif;
		
		$tokenObj->addSessionToken($userId, $tokenObj->generateToken(), "web", $_POST["clientId"]);
		echo 1;
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["userId"]) && isset($_POST["token"]) && isset ($_POST["action"]) && $_POST["action"] == "logout"):
	$tokenObj->deleteSessionToken($_POST["userId"], "mobile", $_POST["token"]);
elseif (isset($_POST["userId"]) && isset($_POST["token"])):
	if ((new Token)->verifySessionToken($_POST["userId"], $_POST["token"], "mobile")):
		$user = new User($_POST["userId"]);

		$result = [
			"userId" => $_POST["userId"],
			"token" => $_POST["token"],
			"firstname" => $user->getFirstname(),
			"lastname" => $user->getLastname()
		];
		echo json_encode($result);
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["clientId"])):
	if ((new Token)->checkSessionId($_POST["clientId"])):
		echo 1;
	else:
		echo 0;
	endif;
else:
	header("HTTP/1.0 404 Not Found");
endif;

?>