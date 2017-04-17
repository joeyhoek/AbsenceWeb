<?php 

namespace Team10\Absence\Model;
use Team10\Absence\Model\Sha3 as Sha3;
use Team10\Absence\Model\Interfaces\Encryption as EncryptionInterface;

require_once("sha3.php");
require_once("interfaces/encryption.php");

final class Encryption implements EncryptionInterface {	
	public function encrypt($data) {
		// Generate random key for used encryption (AES-256) 
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
		// Include encrypt/ decrypt key
		require_once("keys.php");
		$key = pack('H*', base64_decode(DATAKEY));
		
		// Encrypt
		$encryptedData = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
		
		// Return decryptable string
		return base64_encode($iv . $encryptedData);
	}
	
	public function decrypt($encryptedData) {
		// Decode string
		$encryptedData = base64_decode($encryptedData);
		
		// Retrieve random key for used encryption (AES-256)
      	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    	$iv = substr($encryptedData, 0, $iv_size);
		
		// Include encrypt/ decrypt key
		require_once("keys.php");
		$key = pack('H*', base64_decode(DATAKEY));
		
		// Decrypt string
		$encryptedData = substr($encryptedData, $iv_size);
		$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedData, MCRYPT_MODE_CBC, $iv);
		
		// Remove empty characters
		$data = str_replace("\0", "", $data);
		
		// Return plaintext
		return $data;
	}
	
	public function hash($data, $salt = false) {
		// Include encrypt/ decrypt key
		require_once("keys.php");
		
		// Create salt and calculate hash (SHA3-512 with a 4098-bit key)
		if (!$salt):
			$salt = base64_encode(uniqid(mt_rand(), true));	
			$hash = $salt . Sha3::hash($data . $salt . base64_decode(HASHKEY), 512);
		
			// Encrypt hash and return encoded string
			$hash = base64_encode($this->encrypt($hash));
		else:
			$hash = $salt . Sha3::hash($data . $salt . base64_decode(HASHKEY), 512);
		endif;
		
		// Return appropriate hashtype
		return $hash;
	}
	
	public function match_hash($data, $hash) {
		// Decode hash
		$decodedHash = base64_decode($hash);
		
		// Decrypt hash
		$decryptedHash = $this->decrypt($decodedHash);
		
		// Get salt and calculate new hash (SHA3-512 with a 4098-bit key)
		$salt = substr($decryptedHash, 0, 44);
		$hash = $this->hash($data, $salt);
		
		// Match hashes and return result
		if ($hash == $decryptedHash):
			return true;
		else:
			return false;
		endif;
	}	
}

?>