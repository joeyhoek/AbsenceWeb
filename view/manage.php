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

<style>

		
	
</style>  
      
<div class="containerManage">

	<div class="columnLeftManage">
		<div class="columnLeftManage2">
			<div class="image-upload">
				<label for="file-input">
				<i class="fa fa-upload" aria-hidden="true"></i>
				</label>

				<input id="file-input" type="file" name="csv" accept=".csv" required/>
			</div>
			<input type="submit" class="button csvButton" value="Upload">
		</div>

	</div>

		<div class="columnRightManage">
			  	<div class="columnRightManage2">
				  
					<div class="tab">
						<button class="tablinks active" onclick="openCity(event, 'users')">Users</button>
						<button class="tablinks" onclick="openCity(event, 'classrooms')">Classrooms</button>
						<button class="tablinks" onclick="openCity(event, 'locations')">Locations</button>
					</div>
					<div id="searchBar">
						<div id="filter">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</div>
						<input id="searchBox" type="text" placeholder="Search..." />
						<div id="searchButton" onclick="search();">
							<i class="fa fa-search" aria-hidden="true"></i>
						</div>
						<div id="resultsManage">

						</div>
					</div>
					<div id="users" style="display: block;">
						<div class="userInfo">

						</div>
					</div>

					<div id="classrooms"> 
					</div>

					<div id="locations" class="tabcontent">
						<h3>Locations</h3>
						<p>Tokyo is the capital of Japan.</p>
					</div>
			</div>
	</div>
			<div id="helper2">
				<img class="lijn" src="view/images/lijn.png"></img>
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
		document.getElementById(	cityName).style.display = "block";
		evt.currentTarget.className += " active";
}
	
	document.getElementById("file-input").addEventListener('change', function() {
		if (document.getElementById("file-input").value !== "") {
			document.getElementsByClassName("fa-upload")[0].classList.add('green');
		} else {
			document.getElementsByClassName("fa-upload")[0].classList.remove('green');
		}
	});
	
	function search() {
		var search = document.getElementById("searchBox").value;
		if (search == "" || search == null) {
			document.getElementById("resultsManage").innerHTML = "";
		} else {
			var http = new XMLHttpRequest();
			var type = document.getElementsByClassName("active")[0].innerHTML.toLowerCase();
			var params = "search=" + search + "&type=" + type;

			http.open("POST", "<?php echo PROTOCOL . DOMAIN . ROOT; ?>webClient", true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					var resultsManage = JSON.parse(this.responseText);
					var html = "";
					var totalCount = 0;
					
					if (typeof resultsManage.students != "undefined") {
						var count = 0;
						for (var result in resultsManage.students) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=students&id=" + resultsManage.students[result].id + "'><div class='result result-" + totalCount + "'><i>(" + resultsManage.students[result].id + ")</i> " + resultsManage.students[result].firstname + " " + resultsManage.students[result].lastname + "</div></a><i class='fa fa-trash-o' aria-hidden='true'></i><i class='fa fa-pencil' aria-hidden='true'></i>";
							totalCount++;
						}
					}

					if (typeof resultsManage.teachers != "undefined") {
						var count = 0;
						for (var result in resultsManage.teachers) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=teachers&id=" + resultsManage.teachers[result].id + "'><div class='result result-" + totalCount + "'><i>(" + resultsManage.teachers[result].id + ")</i> " + resultsManage.teachers[result].firstname + " " + resultsManage.teachers[result].lastname + "</div></a><i class='fa fa-trash-o' aria-hidden='true'></i><i class='fa fa-pencil' aria-hidden='true'></i>";
							totalCount++;
						}
					}
					
					if (typeof resultsManage.classrooms != "undefined") {
						var count = 0;
						for (var result in resultsManage.classrooms) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=teachers&id=" + resultsManage.classrooms[result].id + "'><div class='result result-" + totalCount + "'>" + resultsManage.classrooms[result].code + "</div></a><i class='fa fa-trash-o' aria-hidden='true'></i><i class='fa fa-pencil' aria-hidden='true'></i>";
							totalCount++;
						}
					}
					
					if (typeof resultsManage.locations != "undefined") {
						for (var result in resultsManage.locations) {
							html = html + "<a href='/overview?type=teachers&id=" + resultsManage.locations[result].id + "'><div class='result result-" + totalCount + "'>" + resultsManage.locations[result].name + "</div></a><i class='fa fa-trash-o' aria-hidden='true'></i><i class='fa fa-pencil' aria-hidden='true'></i>";
							totalCount++;
						}
					}

					document.getElementById("resultsManage").innerHTML = html;
				} 
			};
			http.send(params);
		}
	}

	document.getElementById("searchBox").onkeyup = function () {
		search();
	};
</script>	
	

