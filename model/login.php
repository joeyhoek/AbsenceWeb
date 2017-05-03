<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Encryption as Encryption;
use Team10\Absence\Model\User as User;

class Login {
	public function checkLogin($username, $password) {
		$user = new User;
		if (strpos($username, "@")) {
			$id = $user->getIdFromEmail($username);
		} else {
			if ($username[0] !== "s" && is_numeric($username)) {
				$id = "s" . $username;
			} else {
				$id = $username;
			}
		}
		
		$hashedPassword = $user->getPassword($id);
		
		// Check if user exists and passwords match
		if ($hashedPassword && (new Encryption)->match_hash($password, $hashedPassword)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkToken($id, $token) {
		
	}
}

?>