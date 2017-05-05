<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Encryption as Encryption;
use Team10\Absence\Model\User as User;

/*
echo "password: " . (new Encryption)->encrypt((new Encryption)->hash("ken"));
echo "<br> firstname: " . (new Encryption)->encrypt("Racha");
echo "<br> lastname: " . (new Encryption)->encrypt("Stapper");
echo "<br> email: " . (new Encryption)->encrypt("s1108945@student.windesheim.nl");
echo "<br> sex: " . (new Encryption)->encrypt("2");
echo "<br> dayOfBirth: " . (new Encryption)->encrypt("1992-06-22");
echo "<br> notes: " . (new Encryption)->encrypt("IAMGIRL");
*/


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
		
		$user = new User($id);
		$hashedPassword = $user->getPassword();
		
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