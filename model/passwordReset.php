<?php

namespace Team10\Absence\Model;

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
}


?>