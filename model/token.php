<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\User as User;
use Team10\Absence\Model\Connection as Connection;

class Token {
	public function getRandomBytes($length = 32) {
		$bytes = openssl_random_pseudo_bytes($length, $cryptoStrong);
		if ($bytes !== false && $cryptoStrong === true) {
			return $bytes;
		}
		else {
			throw new Exception("Unable to generate secure token from OpenSSL.");
		}
	}
	
	public function generateToken($length = 80){
		return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length+1))),0,$length);
	}
	
	public function sendToken($email) {
		$token = $this->generateToken();
		if ((new User((new User)->getIdFromEmail($email)))->getFirstname() !== false) {
			(new User((new User)->getIdFromEmail($email)))->storeForgotToken($token);
			$title = "Password Recovery";
			$message = "Click <a href='" . PROTOCOL . DOMAIN . ROOT . "resetPassword?token=$token'>here</a> to reset your password.";
			$headers = "From: Absence <info@absence.com>\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			if (mail($email, $title, $message, $headers)) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function checkToken($token) {
		$result = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM users WHERE forgotToken = '" . $token . "'");
		if ($result !== false && $result !== NULL) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkSessionToken($userId, $client) {
		$result = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM sessions WHERE userId = '" . $userId . "' AND client = '" . $client . "'");
		if ($result !== false && $result !== NULL) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verifySessionToken($userId, $token, $client) {
		$result = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT token FROM sessions WHERE userId = '" . $userId . "' AND client = '" . $client . "'")["token"];
		if ($result !== false && $result !== NULL && $result == $token) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteSessionToken($userId, $client, $token = false) {
		if ($token == false) {
			(new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("DELETE FROM sessions WHERE userId = '" . $userId . "' AND client = '" . $client . "'");
		} else {
			(new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("DELETE FROM sessions WHERE userId = '" . $userId . "' AND client = '" . $client . "' AND token = '" . $token . "'");
		}
	}
	
	public function addSessionToken($userId, $token, $client, $sessionId = NULL) {
		(new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("INSERT INTO sessions (userId, sessionId, token, client) VALUES ('" . $userId . "', '" . $sessionId . "', '" . $token . "', '" . $client . "')");
	}
	
}

	


?>