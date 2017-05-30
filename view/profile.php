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
				<form method="POST">
					<input name="oldPassword" class="input" type="password" placeholder="Current Password" required>
					<input name="newPassword" class="input" type="password" placeholder="New Password" required>
					<input name="confirmPassword" class="input" type="password" placeholder="Confirm Password" required>
					<input class="button" type="submit" value="Reset Password">
				</form>
			</div>
		</div>
	</div>
	<div id="helper" class="helperProfile">
		<img class="lijn" src="view/images/lijn.png" />
	</div>
</div>