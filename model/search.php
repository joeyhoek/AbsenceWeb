<?php

namespace Team10\Absence\Model;
use Team10\Absence\Model\Connection as Connection;
use Team10\Absence\Model\Encryption as Encryption;
use Team10\Absence\Model\User as User;

class Search {
	public function __construct($search) {
		$this->search = strtolower(htmlspecialchars($search));
		$this->connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
	}
	
	private function users($students) {
		$search = $this->search;
		$connection = $this->connection;
		if ($students) {
			$results = $connection->query("SELECT id, firstname, lastname FROM users WHERE roleId = 1");
		} else {
			$results = $connection->query("SELECT id, firstname, lastname FROM users WHERE roleId != 1");
		}
		$encryption = new Encryption;
		
		$count = 0;
		foreach ($results as $result) {
			$results[$count]["firstname"] = $encryption->decrypt($results[$count]["firstname"]);
			$results[$count]["lastname"] = $encryption->decrypt($results[$count]["lastname"]);
			
			if (strpos(strtolower($results[$count]["id"]), $search) !== false || $search == strtolower($results[$count]["id"]) || strpos(strtolower($results[$count]["firstname"]), $search) !== false || $search == strtolower($results[$count]["firstname"]) || strpos(strtolower($results[$count]["lastname"]), $search) !== false || $search == strtolower($results[$count]["lastname"])) {
				$count++;
				continue;
			} else {
				unset($results[$count]);
				$count++;
			}
		}
		
		if ($results) {
			return $results;
		} else {
			return false;
		}
	}
	
	private function courses() {
		$search = $this->search;
		$connection = $this->connection;
		$results = $connection->query("SELECT * FROM courses WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ORDER BY name, code");
		
<<<<<<< HEAD
		
		
		if (isset($results["name"])) {
			$result[0] = $results;
			return $result;
		} if (!$results) {
=======
		if (!$results) {
>>>>>>> origin/master
			return false;
		} else {
			return $results;
		}
	}
	
	private function classes() {
		$search = $this->search;
		$connection = $this->connection;
		$results = $connection->query("SELECT * FROM classes WHERE code LIKE '%" . $search . "%' ORDER BY code");
		
<<<<<<< HEAD
		if (isset($results["code"])) {
			$result[0] = $results;
			return $result;
		} if (!$results) {
=======
		if (!$results) {
>>>>>>> origin/master
			return false;
		} else {
			return $results;
		}
	}
	
	private function locations() {
		$search = $this->search;
		$connection = $this->connection;
		$results = $connection->query("SELECT * FROM locations WHERE name LIKE '%" . $search . "%' ORDER BY name");
		
<<<<<<< HEAD
		if (isset($results["name"])) {
			$result[0] = $results;
			return $result;
		} if (!$results) {
=======
		if (!$results) {
>>>>>>> origin/master
			return false;
		} else {
			return $results;
		}
	}
	
	public function getResults($students, $teachers, $courses, $classes, $locations) {
		$results = [];
		
		if ($students) {
			$result = $this->users(true);
			
			if ($result) {
				$results["students"] = $result;
			}
		} 
		
		if ($teachers) {
			$result = $this->users(false);
			
			if ($result) {
				$results["teachers"] = $result;
			}
		} 
		
		if ($courses) {
			$result = $this->courses();
			
			if ($result) {
				$results["courses"] = $result;
			}
		} 
		
		if ($classes) {
			$result = $this->classes();

			if ($result) {
				$results["classes"] = $result;
			}
		} 
		
		if ($locations) {
			$result = $this->users(false);
			
			if ($result) {
				$results["locations"] = $this->locations();
			}
		}
			
		if (!empty($results)) {
			return $results;
		} else {
			return false;
		}
	}
	
}

//$search = new Search("hoek");
//var_dump($search->getResults(true, true, true, true, false));

//var_dump($search->check());

?>