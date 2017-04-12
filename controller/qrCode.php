<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\QRCode as QRCode;

$output = [
	"type" => "presence",
	"value" => "Kien-Yan"
];
$output = json_encode($output);
$QRCode = new QRCode(300, 300);
$QRCode = $QRCode->Generate($output);

require_once("view/login.php");

?>