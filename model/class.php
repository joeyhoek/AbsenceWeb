<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;

class ClassObj {
	public function __construct($id) {
		$this->connection = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME));
		$this->encryption = new Encryption;
		$this->id = $this->connection->escape($id);
	}
	
	public function getCode() {
		$code = $this->connection->query("SELECT code FROM classes WHERE id = '" . $this->id . "'")["code"];
		return $code;
	}
}

?>