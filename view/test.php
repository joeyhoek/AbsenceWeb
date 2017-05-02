<?php
	namespace Team10\Absence\View;
	use Team10\Absence\Model\User as User;

	$user = new User;
	echo $user->getIdFromEmail("s1102860@student.windesheim.nl");

?>