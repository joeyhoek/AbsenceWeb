<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

class User {
	public function __construct($id = false) {
		$this->connection = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME));
		$this->encryption = new Encryption;
		$this->id = $this->connection->escape($id);
	}
	
	public function getEmail() {
		$email = $this->connection->query("SELECT email FROM users WHERE id = '" . $this->id . "'")["email"];
		return $this->encryption->decrypt($email);
	}
	
	public function getPassword() {
		$password = $this->connection->query("SELECT password FROM users WHERE id = '" . $this->id . "'")["password"];
		return $this->encryption->decrypt($password);
	}
	
	public function getFirstname() {
		$firstname = $this->connection->query("SELECT firstname FROM users WHERE id = '" . $this->id . "'")["firstname"];
		if ($firstname !== false && $firstname !== NULL) {
			return $this->encryption->decrypt($firstname);
		} else {
			return false;
		}
	}
	
	public function getLastname() {
		$lastname = $this->connection->query("SELECT lastname FROM users WHERE id = '" . $this->id . "'")["lastname"];
		return $this->encryption->decrypt($lastname);
	}
	
	public function getSex() {
		$sex = $this->connection->query("SELECT sex FROM users WHERE id = '" . $this->id . "'")["sex"];
		return $this->encryption->decrypt($sex);
	}
	
	public function getDayOfBirth() {
		$dayOfBirth = $this->connection->query("SELECT dayOfBirth FROM users WHERE id = '" . $this->id . "'")["dayOfBirth"];
		return $this->encryption->decrypt($dayOfBirth);
	}
	
	public function getNotes() {
		$notes = $this->connection->query("SELECT notes FROM users WHERE id = '" . $this->id . "'")["notes"];
		return $this->encryption->decrypt($notes);
	}
	
	public function getRole() {
		$role = $this->connection->query("SELECT roleId FROM users WHERE id = '" . $this->id . "'")["role"];
		return $role;
	}
	
	public function getClass() {
		$class = $this->connection->query("SELECT classId FROM users WHERE id = '" . $this->id . "'")["class"];
		return $class;
	}
	
	public function getFaculty() {
		$faculty = $this->connection->query("SELECT facultyId FROM users WHERE id = '" . $this->id . "'")["facultyId"];
		return $class;
	}
	
	public function getForgotToken() {
		$forgotToken = $this->connection->query("SELECT forgotToken FROM users WHERE id = '" . $this->id . "'")["forgotToken"];
		if ($forgotToken !== false && $forgotToken !== NULL && $forgotToken !== "0") {
			return $forgotToken;
		} else {
			return false;
		}
	}
	
	public function getYear() {
		$year = $this->connection->query("SELECT year FROM users WHERE id = '" . $this->id . "'")["year"];
		return $year;
	}
	
	public function getIdFromEmail($email) {
		if (strpos($email, "@")) {
			$email = explode("@", $email);
			if (($email[1] == "student.windesheim.nl" && $email[0][0] == "s") || $email[1] == "docent.windesheim.nl") {
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
	
	public function storeForgotToken($token) {
		return $this->connection->query("UPDATE users SET forgotToken='" . $token . "' WHERE id = '" . $this->id . "'");
	}
	
	public function changePassword($newPassword, $confirmPassword, $token = false) {
		if ($newPassword === $confirmPassword) {
			$password = (new Encryption)->encrypt((new Encryption)->hash($newPassword));
			if ($token !== false) {
				$this->connection->query("UPDATE users SET password = '" . $password . "' WHERE forgotToken = '" . $this->connection->escape($token) . "'");
				$this->connection->query("UPDATE users SET forgotToken = '0' WHERE forgotToken = '" .$this->connection->escape($token) . "'");
			} else {
				$this->connection->query("UPDATE users SET password = '" . $password . '" WHERE id = "' . $this->id . "'");
			}
		} else {
			echo "Passwords do not match";
		}			
	}
	
	public function changeClass() {
		
	}
}

?>