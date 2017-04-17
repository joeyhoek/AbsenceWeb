<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="view/css/ken.css">
</head>


<body>
	
	<div id="column-left">
		
		<div id="qrcode">
			<?php echo "<img src='$QRCodeLink'>";?>
		</div>
		
	</div>
	
	<div id="column-right">
		
		<div id="logo"> <img src="view/images/logo.png" alt="Logo"></div> <br><br>

		<form method="POST">

			<input class="input email" type="text" name="email" required><br>
			<input class="input password" type="password" name="password" required><br>
			<button class="button" type="submit" name="login">Sign In</button> <br>
			<a href="../controller/forgot_password.php">Forgot Password?</a>
		</form>	

<!-- DOUBLE BORDERS TEST
		<div class="borders">
		<input class="borders" type="text">

		</div>
-->

	</div>
	
	<div id="column-middle">
		hoi
		
	</div>
	
	<div id="wit"></div>
	
	<span id="footer">Copyright 2017 © Windesheim Flevoland</span>
	
</body>