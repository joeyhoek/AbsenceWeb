<?php

namespace Team10\Absence\Model;

final class Connection {
	private $host;
	private $dbuser;
	private $dbpass;
	private $dbname;
	
	public function __construct($host, $dbuser, $dbpass, $dbname) {
		$this->host = $host;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
	}
	
	private function connect() {
		$host = $this->host;
		$dbuser = $this->dbuser;
		$dbpass = $this->dbpass;
		$dbname = $this->dbname;

		$connection = mysqli_connect($host, $dbuser, $dbpass, $dbname);
		return $connection;
	}
	
	public function query($query) {
		$connection = $this->connect();
		$stmt = utf8_encode($query);
		$result = mysqli_query($connection, $stmt);
		$connection = null;
		if ($result !== false && $result !== true) {
			if (mysqli_num_rows($result) !== 1) {
				while($row = $result->fetch_assoc()):
					$data[] = $row;
				endwhile;
				return $data;
			} else {
				return mysqli_fetch_assoc($result);
			}
		} else {
			return $result;
		}
	}
}

?>