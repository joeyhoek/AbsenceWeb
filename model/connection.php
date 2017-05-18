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

		$connection = mysqli_connect($host, $dbuser, $dbpass, $dbname) or die("Error establishing connection with database");
		return $connection;
	}
	
	public function escape($value) {
		return mysqli_real_escape_string($this->connect(), $value);
	}
	
	public function query($query, $type = false) {
		$connection = $this->connect();
		$stmt = utf8_encode($query);
		$result = mysqli_query($connection, $stmt);
		if ($result !== false && $result !== true && $type == false) {
			$connection = null;
			return mysqli_fetch_assoc($result);
		} elseif ($type == "insert") {
			$id = mysqli_insert_id($connection);
			$connection = null;
			return $id;
		} else {
			$connection = null;
			return false;
		}
	}
}

?>