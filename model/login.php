<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\login as login;

function loggedIn() {
	if (isset($_SESSION['username'])) {
		return true;
	} else {
		return false;
	}
}

?>