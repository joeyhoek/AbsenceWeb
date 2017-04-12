<?php

use InnovateWebdesign\Modules\QRCodeLogin\Model\QRCode as QRCode;

require_once("model/qr-code.php");

$output = [
	"type" => "presence",
	"value" => "Kien-Yan"
];
$output = json_encode($output);
$QRCode = new QRCode(300, 300);
$QRCode = $QRCode->Generate($output);

require_once("view/login.php");





?>