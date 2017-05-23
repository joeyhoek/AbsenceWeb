<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

class Course {
	public function __construct($id) {
		$this->connection = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME));
		$this->encryption = new Encryption;
		$this->id = $this->connection->escape($id);
	}
	
	public function getName() {
		$name = $this->connection->query("SELECT name FROM courses WHERE id = '" . $this->id . "'")["name"];
		return $name;
	}
	
	public function getCode() {
		$code = $this->connection->query("SELECT code FROM courses WHERE id = '" . $this->id . "'")["code"];
		return $code;
	}
}

?>