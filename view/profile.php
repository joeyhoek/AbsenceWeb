<style>
	.helperProfile .lijn {
		height: 450px !important;
		animation: none;
	}
	
	.color {
		color: #464648;
	}
	
	.profileName {
		text-align: center;
		margin: 10px auto 0;
		width: 361px; 
		max-width: 100%;
	}
	.profileInfo {
		text-align: left;
		margin-top: 20px;
	}
	
	.profileInfo p {
		margin-bottom: 8px;
	}
	
	.profileInfo p b {
		font-weight: bold !important;
	}
	
	.notesTitle p {
		margin-top: -20px;
		margin-bottom: 20px;
		text-align: left;
	}
	
	.notesTitle b {
		color: #464648;
		font-weight: bold !important;
		text-align: left;
		position: relative;
		width: calc(50% - 30px);
	}
	
	.notesBox {
		color: #464648;
		background-color: #f9fcfd;
	  	box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		min-width: 65%;
	  	padding: 20px;
		text-align: left;
	}
	
	.passwordTitle {
		color: #464648;
		font-weight: bold !important;
		text-align: left;
		position: relative;
		width: 100%;
		margin-top: 40px;
		margin-bottom: 20px;
	}
	
	.passwordBox {
		background-color: #f9fcfd;
	  	box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		min-width: 65%;
	  	padding: 25px;
		text-align: left;
	}
	
	.passwordBox input[type="text"]{
		outline: 0px;
	}
	
	.passwordBox .button { 
		width: 144px;
		height: 35px;
		margin-left: 6px;
	}

	.column_racha {
		width: calc(100% - 60px);
		margin-left: 30px;
		top: 50%;
		left: 0;
		position: relative;
		transform: translate(0, -50%);
	}
	
	.profileContent { 
		color: #464648;
		position: relative;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);	
	}

	.container { 
		width: 100%;
		height: 100%;
	}
	
	.container_left { 
		height: 100%;
		width: 50%;
		float: left;
	}
	
	.container_right{ 
		height: 100%;
		width: 50%;
		float: right;
	}
	
	@media only screen and (max-width: 675px){
		.lijn { 
			display: none !important;
		}
		
		.container_left, .container_right{ 
			height: auto;
			margin: 0;
			float: none;
			width: 100%
		}
		
		.profileContent{
			top: 0;
			left: 0;
			transform: translate(0);
		}
		
		.profileName { 
			width: 360px;
			margin: 10px auto 0;
			max-width: 100%;
		}
		.profileInfo{
			float: none;
			max-width: 100%;
			margin-left: 0;
		}
		.container_right{
			margin: auto;
			float: none;
		}
		
		.column_racha{
			float: none;
			margin:0;
			width: 100%;
			transform: none;
		}
		
		.notesTitle{ 
			margin-top: 20px;
		}
		
		.notesBox {
			width: 100% auto;
		}
		
		.passwordBox { 
			margin-bottom: 25px;
		}
	}
	
	.profilePf {
		width: 150px;
		margin: 0 auto;
		height: 150px;
		overflow: hidden;
		border-radius: 50%;
	}

	.profilePf img {
		width: 100%;
		margin-top: -11%;
	}
</style>

<div class="container profilePage">
	<div class="container_left">
		<div class="profileContent">
			<?php if ($user->getProfilePicture()) { ?>
			<div class="profilePf"><img src="<?php echo $user->getProfilePicture() ; ?>" /></div>
			<?php } else { ?>
			<i class="faf fa fa-user color"  aria-hidden="true"></i>
			<?php } ?>
			<div class="profileName">
				<?php echo $user->getFirstname() . " " . $user->getLastname(); ?><br>
				<?php echo $_SESSION["userId"]; ?><br><br>

				<div class="profileInfo">
					<p><b>Email:</b> <?php echo $user->getEmail(); ?></p>
					<p><b>Faculty:</b> <?php echo $user->getFaculty(); ?></p>
					<p><b>Class:</b> <?php echo $user->getClass(); ?></p>
				</div>
			</div>
		</div>
	 </div>			
		
	<div class="container_right">
		<div class="column_racha">
			<div class="notesTitle">
				<br>
				<p><b>Notes:</b></p>	
			</div>
			<div class="notesBox">
				<?php echo $user->getNotes(); ?>
			</div>
			<div class="passwordTitle">
				<b>Reset password:</b>
			</div>
			<div class="passwordBox">
				<form>
					<input class="input" type="password" placeholder="Current Password">
					<input class="input" type="password" placeholder="New Password">
					<input class="input" type="password" placeholder="Confirm Password">
					<input class="button" type="submit" value="Reset Password">
				</form>
			</div>
		</div>
	</div>
	<div id="helper" class="helperProfile">
		<img class="lijn" src="view/images/lijn.png" />
	</div>
</div>