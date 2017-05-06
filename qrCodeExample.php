<?php
	session_start();

	use Team10\Absence\Model\QRCode as QRCode;
	use Team10\Absence\Model\Connection as Connection;

	require("require.php");
	
	if(!isset($_SESSION["userid"])):
		$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
		$result = $connection->query("SELECT * FROM tokens WHERE clientid = '" . session_id() . "'");

		if ($result !== false):
			$_SESSION["userid"] = $result["userid"];
			$_SESSION["token"] = $result["token"];
		endif;
	endif;

	if (isset($_GET["action"]) && $_GET["action"] == "logout"):
		$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
		$connection->query("DELETE FROM tokens WHERE clientid = '" . session_id() . "'");
		session_destroy();
		header("Refresh: 0; url='example.php'");
	endif;
		
	if (isset($_SESSION["userid"]) && isset($_SESSION["token"])):
		$connection = new Connection("localhost", "innovate_absence", "TDz8e0lOmL", "innovate_absence");
		$result = $connection->query("SELECT * FROM users, tokens WHERE tokens.userid = users.userid AND clientid = '" . session_id() . "'");
		if ($_SESSION["token"] == $result["token"]):
			echo "Hallo " . $result["firstname"] . " " . $result["lastname"];
			echo "<br /><br /><a href='?action=logout'>Logout</a>";
		endif;
	else:
		$qrcode = new QRCode(300, 300);
		$qrsource = $qrcode->Generate();
		echo("<body id='main'><img src='" . $qrsource . "'/>");
?>
	<script>
		function checkIfScanned(sessionid) {
			var xhttp = new XMLHttpRequest();
			var url = "<?php echo $PROTOCOL . $HOST . $ROOT; ?>/mobileClient";
			var params = "clientid=" + sessionid;
			xhttp.open("POST", url, true);

			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.onreadystatechange = function() {
				if(xhttp.readyState == 4 && xhttp.status == 200) {
					if (xhttp.responseText !== "0") {
						setTimeout(function(){ window.location.reload(); }, 1);
					}
					xhttp = null;
					setTimeout(checkIfScanned(sessionid), 500);
				}
			};
			xhttp.send(params);
		}
		
		window.onload = function () {
			checkIfScanned("<?php echo session_id(); ?>");
		};
	</script></body>
<?php
	endif;
?>