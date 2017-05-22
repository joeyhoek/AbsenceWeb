<?php

namespace Team10\Absence\Controller;
use Team10\Absence\Model\QRCode as QRCode;

$QRCode = new QRCode(250, 250);

if ($page !== "login") {
	$output = [
		"type" => "presence",
		"value" => "Kien-Yan"
	];
	
	$output = json_encode($output);
	$QRCodeLink = $QRCode->Generate($output);
} else {
	$QRCodeLink = $QRCode->Generate();
}

// This is for desktop client
$QRCodeDesktopLink = $QRCode->GenerateDesktopClientLink($QRCodeLink, "HDBJHAS", "ADHKJAH", "134342");
echo "<a href='" . $QRCodeDesktopLink . "'>Klik hier</a> om de Desktop Client te openen";

?>