<?php

if (isset($_GET["url"])) {
	
	echo "SWITCH";
	
} else {
	
	require("controller/qrCodeController.php");
	
}





?>