<?php

use InnovateWebdesign\Modules\QRCodeLogin\Model\Connection as Connection;
use InnovateWebdesign\Modules\QRCodeLogin\Model\Encryption as Encryption;

require_once("model/connection.php");
require_once("model/encryption.php");

if (isset($_POST["id"]) && isset($_POST["token"]) && isset($_POST["token"])):
	return json_encode([
		"present" => 25,
		"toLate" => 3,
		"absent" => 105
	]);
endif;

?>