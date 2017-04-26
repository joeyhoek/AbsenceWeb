<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="view/css/login.css" media="screen and (min-width:10px)">
</head>


<body>
	<div class="container">
		<div class="container2">
			<div id="column-left">

				<div id="qrcode">
					<?php echo "<img src='$QRCodeLink'>";?>
				</div>

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
							<span class="forgot_password"><a href="../controller/forgot_password.php">Forgot Password?</a></span>
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