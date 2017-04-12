<?php

namespace InnovateWebdesign\Modules\QRCodeLogin\Model;

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
		
		$qrcode = "https://chart.googleapis.com/chart?chs=" . $this->width . "x" . $this->height . "&cht=qr&chl=" . $output . "&choe=UTF-8";
		return $qrcode;
	}
}

?>