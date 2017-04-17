<?php

use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

require_once("../model/connection.php");
require_once("../model/encryption.php");

if (isset($_POST["username"]) && isset($_POST["password"])):
	$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
	$result = $connection->query("SELECT * FROM users WHERE username = '" . $_POST["username"] .  "'");
	if ($result !== false && $_POST["username"] == $result["username"] && $_POST["password"] == $result["password"]):
		unset($result["password"]);
		if ($connection->query("SELECT * FROM appclients WHERE userid = " . $result["userid"]) !== false):
			$connection->query("DELETE FROM appclients WHERE userid = " . $result["userid"]);
		endif;
		$token = bin2hex(openssl_random_pseudo_bytes(20));
		$connection->query("INSERT INTO appclients (userid, token) VALUES (" . $result["userid"] . ", '" . $token . "')");
		$result["token"] = $token;
		echo json_encode($result);
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
else:
	header("HTTP/1.0 404 Not Found");
endif;

?>