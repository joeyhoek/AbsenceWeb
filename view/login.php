<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="view/css/login.css">
</head>


<body>
	<div class="container">
	<div id="column-left">
		
		<div id="qrcode">
			<?php echo "<img src='$QRCodeLink'>";?>
		</div>
		
	</div>
	
	<div id="column-right">
		
		<div id="logo"> <img src="view/images/logo.png" alt="Logo"></div> <br><br>

		<form method="POST">

			<input class="input email" type="text" name="email" required>
			<img class="emailLogo" src="view/images/login.png">
			<br>
			<input class="input password" type="password" name="password" required>
			<img class="passwordLogo" src="view/images/slotje.png">
			<br>
			
			<button class="button" type="submit" name="login">Sign In</button> <br>
			<span class="forgot_password"><a href="../controller/forgot_password.php">Forgot Password?</a></span>
		</form>	

<!-- DOUBLE BORDERS TEST
		<div class="borders">
		<input class="borders" type="text">

		</div>
-->

	</div>
	
		<div id="container2"><div id="wit"></div></div>
	
	<span id="footer">Copyright 2017 © Windesheim Flevoland</span>
	</div>
</body>