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
			mail($email, $title, $message, $headers);
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
}

	


?>