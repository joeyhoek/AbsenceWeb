<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

class User {
	public function __construct() {
		$this->connection = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME));
		$this->encryption = new Encryption;
	}
	
	public function getEmail($id) {
		$email = $this->connection->query("SELECT email FROM users WHERE id = '" . $id . "'")["email"];
		return $this->encryption->decrypt($email);
	}
	
	public function getPassword($id) {
		$password = $this->connection->query("SELECT password FROM users WHERE id = '" . $id . "'")["password"];
		return $this->encryption->decrypt($password);
	}
	
	public function getIdFromEmail($email) {
		if (strpos($email, "@")) {
			$email = explode("@", $email);
			if ($email[1] == "student.windesheim.nl" && $email[0][0] == "s") {
				return $email[0];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function addUser() {
		
	}
	
	public function deleteUser() {
		
	}
	
	public function changePassword() {
		
	}
	
	public function changeClass() {
		
	}
}

?>