<?php

namespace Team10\Absence\Controller;

require_once("controller/require.php");

if (isset($_GET["url"])) {
	
	echo "SWITCH";
	
} else {
	// If no url go to login page
	$page = "Login";
	require_once("controller/qrCode.php");
}

?>