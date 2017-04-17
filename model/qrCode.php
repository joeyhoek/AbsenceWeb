<?php

namespace Team10\Absence\Model;

final class QRCode {
	private $width;
	private	$height;
	
	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}
	
	public function Generate($output = false) {
		if (!$output):
			$output = [
				"type" => "login",
				"value" => session_id()
			];
			$output = json_encode($output);
		endif;
		
		$qrcode = "https://api.qrserver.com/v1/create-qr-code/?size=" . $this->width . "x" . $this->height . "&data=" . $output;
		return $qrcode;
	}
	
	public function GenerateDesktopClientLink($qrCode, $userId, $token, $classId) {
		$qrCode = str_replace("&", "$", $qrCode);
		return "absence:\\\\" . $qrCode . "%" . $userId . "%" . $token . "%" . $classId;
	}
}

?>