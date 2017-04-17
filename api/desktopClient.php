<?php

namespace Team10\Absence\Api\DesktopClient;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

require_once("../model/connection.php");
require_once("../model/encryption.php");

if (isset($_POST["userId"]) && isset($_POST["token"]) && isset($_POST["classId"])):
	echo json_encode([
		"present" => 25,
		"toLate" => 3,
		"absent" => 105,
		"finished" => "notDone"
	]);
endif;

?>