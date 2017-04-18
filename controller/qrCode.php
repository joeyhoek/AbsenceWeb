<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\QRCode as QRCode;

$output = [
	"type" => "presence",
	"value" => "Kien-Yan"
];
$output = json_encode($output);
$QRCode = new QRCode(250, 250);
$QRCodeLink = $QRCode->Generate($output);
$QRCodeDesktopLink = $QRCode->GenerateDesktopClientLink($QRCodeLink, "HDBJHAS", "ADHKJAH", "134342");
echo "<a href='" . $QRCodeDesktopLink . "'>Klik hier</a> om de Desktop Client te openen";
//require_once("view/login.php");

?>