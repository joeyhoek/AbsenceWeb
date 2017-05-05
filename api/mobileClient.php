<?php

use Team10\Absence\Model\Login as Login;
use Team10\Absence\Model\Token as Token;
use Team10\Absence\Model\User as User;

if (isset($_POST["username"]) && isset($_POST["password"])):
	if ((new Login)->checkLogin($_POST["username"], $_POST["password"])):
		$username = $_POST["username"];
		if (strpos($username, "@")):
			$id = $user->getIdFromEmail($username);
		else:
			if ($username[0] !== "s" && is_numeric($username)):
				$id = "s" . $username;
			else:
				$id = $username;
			endif;
		endif;

		if ((new Token)->checkSessionToken($id, "mobile") !== false):
			(new Token)->deleteSessionToken($id, "mobile");
		endif;

		$token = (new Token)->generateToken();
		(new Token)->addSessionToken($id, $token, "mobile");

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
	if ((new Token)->sendToken($_POST["username"])):
		http_response_code(200);
		echo 1;
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["userid"]) && isset($_POST["token"]) && isset($_POST["clientid"])):
	$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
	$userid = $connection->query("SELECT * FROM tokens WHERE userid = " . $_POST["userid"]);
	$token = $connection->query("SELECT * FROM tokens WHERE token = '" . $_POST["token"] . "'");
	if ($userid !== false && $token !== false):
		if ($connection->query("SELECT * FROM tokens WHERE clientid = '" . $_POST["clientid"] . "'") !== false):
			$connection->query("DELETE FROM tokens WHERE clientid = '" . $_POST["clientid"] . "'");
		endif;
		$connection->query("INSERT INTO tokens (userid, token, clientid) VALUES (" . $_POST["userid"] . ", '" . bin2hex(openssl_random_pseudo_bytes(20)) . "', '" . $_POST["clientid"] . "')");
		echo 1;
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["userid"]) && isset($_POST["token"]) && isset ($_POST["action"]) && $_POST["action"] == "logout"):
	$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
	$connection->query("DELETE FROM appclients WHERE userid = '" . $_POST["userid"] . "' AND token = '" . $_POST["token"] . "'");
elseif (isset($_POST["userid"]) && isset($_POST["token"])):
	$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
	$result = $connection->query("SELECT * FROM users, appclients WHERE users.userid = appclients.userid AND users.userid = " . $_POST["userid"]);
	if ($result !== false && $result["token"] == $_POST["token"]):
		unset($result["password"]);
		echo json_encode($result);
	else:
		header("HTTP/1.0 403 Forbidden");
	endif;
elseif (isset($_POST["clientid"])):
	$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
	$result = $connection->query("SELECT * FROM tokens WHERE clientid = '" . $_POST["clientid"] . "'");
	if ($result !== NULL):
		echo 1;
	else:
		echo 0;
	endif;
endif;

?>