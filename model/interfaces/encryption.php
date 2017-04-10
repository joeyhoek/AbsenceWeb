<?php

namespace InnovateWebdesign\Modules\QRCodeLogin\Model\Interfaces;

interface Encryption {
	public function encrypt($data);
	
	public function decrypt($encryptedData);
	
	public function hash($data, $salt = false);
	
	public function match_hash($data, $hash);
}

?>