<?php

	use Team10\Absence\Model\Encryption as Encryption;
	use Team10\Absence\Model\Connection as Connection;

	function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

	function readCSV($filename) {
		if (($handle = fopen($filename, "r")) !== false) {
			$filesize = filesize($filename);
			$aData = array();
			$count = 0;
			while (($data = fgetcsv($handle, $filesize, ";")) !== false) {
				if ($count == 0) {
					for($i = 0; $i < count($data); $i++) {
						$columns[$i] = $data[$i];
					}
				} else {
					if (in_array_r(preg_split('/\s+/', $data[1])[0], $aData)) {
						continue;
					}
					for($i = 0; $i < count($data); $i++) {
						for ($j = 0; $j < count($columns); $j++) {
							 $aData[$count - 1][$columns[$j]] = $data[$j];
						}
					}
				}
				$count++;
			}
			fclose($handle);
			for ($i = 0; $i < count($aData); $i++) {
				$aData[$i]["Studentnummer"] = "s" . $aData[$i]["Stud.nr."];
				if ($aData[$i]["Tussenvoegsel"] !== "-") {
					$aData[$i]["Achternaam"] = $aData[$i]["Tussenvoegsel"] . " " . $aData[$i]["Achternaam"];
				}
				unset($aData[$i]["Tussenvoegsel"]);
			}
			return $aData;
		}
	}

	function uploadStudents($students) {
		$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
		$encryption = new Encryption;
		
		foreach ($students as $student) {
			$class = htmlspecialchars($student["Klas"]);
			
			/*if (!$connection->query("SELECT firstname FROM users WHERE id = '" . $username . "'")) {
				$connection->query("INSERT INTO users VALUES ('" . $username . "', '0', '" . $firstname . "', '" . $lastname . "', '" . $email . "', '1', '1', '1', '1', '0', '0')");
			} else {
				//$connection->query("INSERT INTO users FROM users WHERE id = '" . $username . "'");
			}*/
		}
		
		foreach ($students as $student) {
			$username = htmlspecialchars($student["Studentnummer"]);
			$firstname = $encryption->encrypt($student["Roepnaam"]);
			$lastname = $encryption->encrypt($student["Achternaam"]);
			$email = $encryption->encrypt($student["E-mailadres"]);
			
			if (!$connection->query("SELECT firstname FROM users WHERE id = '" . $username . "'")) {
				$connection->query("INSERT INTO users VALUES ('" . $username . "', '0', '" . $firstname . "', '" . $lastname . "', '" . $email . "', '1', '1', '1', '1', '0', '0')");
			} else {
				//$connection->query("INSERT INTO users FROM users WHERE id = '" . $username . "'");
			}
		}
		
		return(true);
	}

	if (isset($_FILES['csv'])) {
		$filename = $_FILES['csv']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ($ext == "csv") {
			$students = readCSV($_FILES['csv']['tmp_name']);
			if (!uploadStudents($students)) {
				echo "failure";
			} else {
				echo "gelukt";
			}
		} else {
			echo "Wrong file type. File must be a .csv file";
		}
	}

?>
  

<form  method="post" enctype="multipart/form-data">
 	Select image to upload:
    <input type="file" name="csv" accept=".csv" required>
    <input type="submit" value="Upload">
</form>