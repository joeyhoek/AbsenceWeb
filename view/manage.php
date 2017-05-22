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
				unset($aData[$i]["Stud.nr."]);
				if ($aData[$i]["Tussenvoegsel"] !== "") {
					$aData[$i]["Achternaam"] = $aData[$i]["Tussenvoegsel"] . " " . $aData[$i]["Achternaam"];
				}
				unset($aData[$i]["Tussenvoegsel"]);
				$aData[$i]["Groepsnaam"] = $aData[$i][key($aData[$i])];
			    unset($aData[$i][key($aData[$i])]);
			}
			return $aData;
		}
	}

	function uploadStudents($students) {
		$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
		$encryption = new Encryption;
		
		$count = 0;
		
		foreach ($students as $student) {
			$class = htmlspecialchars($student["Groepsnaam"]);
			$result = $connection->query("SELECT id FROM classes WHERE code = '" . $class . "'");
			
			if (!$result) {
				$id = $connection->query("INSERT INTO classes (code) VALUES ('" . $class . "')", "insert");
				$students[$count]["Klas"] = $id;
			} else {
				$students[$count]["Klas"] = $result["id"];
			}
			
			$comakership = htmlspecialchars($student["Comakership"]);
			$result = $connection->query("SELECT id FROM comakerships WHERE code = '" . $comakership . "'");
			
			if (!$result) {
				$id = $connection->query("INSERT INTO comakerships (code) VALUES ('" . $comakership . "')", "insert");
				$students[$count]["Comakerships"] = $id;
			} else {
				$students[$count]["Comakerships"] = $result["id"];
			}
			
			$count++;
		}

		foreach ($students as $student) {
			$username = htmlspecialchars($student["Studentnummer"]);
			$firstname = $encryption->encrypt($student["Roepnaam"]);
			$lastname = $encryption->encrypt($student["Achternaam"]);
			$email = $encryption->encrypt($student["E-mailadres"]);
			
			if (!$connection->query("SELECT firstname FROM users WHERE id = '" . $username . "'")) {
				$connection->query("INSERT INTO users VALUES ('" . $username . "', '0', '" . $firstname . "', '" . $lastname . "', '" . $email . "', '1', '1', '" . $student["Klas"] . "', '" . $student["Comakerships"] . "', '0', '0')");
			} else {
				$connection->query("UPDATE users SET firstname = '" . $firstname . "', lastname = '" . $lastname . "', email = '" . $email . "', classId = '" . $student["Klas"] . "', comakershipId = '" . $student["Comakerships"] . "' WHERE id = '" . $username . "'");
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
  
<div class="container">

		<div id="column-left2">
			<form  method="post" enctype="multipart/form-data">
				<div class="image-upload">
				<label for="file-input">
				<i class="fa fa-upload" aria-hidden="true"></i>
				</label>

				<input id="file-input" type="file" name="csv" accept=".csv" required/>
			</div>
		<input type="submit" class="button csvButton" value="Upload">

		
		</div>

		<div id="column-right">
				  
					<div class="tab">
						<button class="tablinks" onclick="openCity(event, 'users')">Users</button>
						<button class="tablinks" onclick="openCity(event, 'classrooms')">Classrooms</button>
						<button class="tablinks" onclick="openCity(event, 'locations')">Locations</button>
					</div>

					<div id="users" class="tabcontent">
						<div class="searchbar">
							<i class="fa fa-search" aria-hidden="true"></i>
						</div>
						<p>London is the capital city of England.</p>
						 <i class="fa fa-pencil" aria-hidden="true"></i>
   						 <i class="fa fa-trash-o" aria-hidden="true"></i>
					</div>

					<div id="classrooms" class="tabcontent">
						<h3>Classrooms</h3>
						<p>Paris is the capital of France.</p> 
					</div>

					<div id="locations" class="tabcontent">
						<h3>Locations</h3>
						<p>Tokyo is the capital of Japan.</p>
					</div>

			<div id="helper2">
				<img class="lijn" src="view/images/lijn.png"></img>
			</div>
			
	</div>
</div>

<script>
	function openCity(evt, cityName) {
		// Declare all variables
		var i, tabcontent, tablinks;

		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}

		// Show the current tab, and add an "active" class to the button that opened the tab
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
}
	
	document.getElementById("file-input").addEventListener('change', function() {
		if (document.getElementById("file-input").value !== "") {
			document.getElementsByClassName("fa-upload")[0].classList.add('green');
		} else {
			document.getElementsByClassName("fa-upload")[0].classList.remove('green');
		}
	});
	
	
	
</script>

