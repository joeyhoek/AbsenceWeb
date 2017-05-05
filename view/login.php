<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="view/css/login.css?v=4" media="screen and (min-width:10px)">
	<link rel="stylesheet" href="view/css/shake.css">
</head>


<body>
	<div class="container">
		<div class="container2">
			<div id="column-left">

				<div id="qrcode">
					<?php echo "<img src='$QRCodeLink'>";?>
				</div>
				<script>
					function checkIfScanned(sessionid) {
						var xhttp = new XMLHttpRequest();
						var url = "<?php echo PROTOCOL . DOMAIN . ROOT; ?>mobileClient";
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
				</script>
			</div>
		
			<div id="column-right">
				<div id="container-right">
					<div id="container-right2">
						<div id="logo"> <img class="logo" src="view/images/logo.png" alt="Logo"></div> <br><br>

						<form method="POST">

							<input class="input email" type="text" name="email" required autocorrect="off" autocapitalize="off" spellcheck="false" autocomplete="new-password">
							<img class="emailLogo" src="view/images/login.png">
							
							<input class="input password" type="password" name="password" required autocorrect="off" autocapitalize="off" spellcheck="false" autocomplete="new-password">
							<img class="passwordLogo" src="view/images/slotje.png">
							<br>

							<button class="button" type="submit" name="login">Sign In</button>	
							<span class="forgot_password"><a href="/forgotPassword">Forgot Password?</a></span>
						</form>	
					</div>
				</div>
	
			</div>
				<div id="helper">
					<img class="lijn" src="view/images/lijn.png"></img>
				</div>
				<div id="container2"><div id="wit"></div></div>
		</div>
			<span id="footer">Copyright 2017 © Windesheim Flevoland</span>
		
	</div>
	<script src="view/js/border-left.js"></script>
</body>