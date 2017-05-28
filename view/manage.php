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
			<form method="post" enctype="multipart/form-data">
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
						<button class="tablinks active userr" onclick="openCity(event, 'users')">Users</button>
						<button class="tablinks classroomss" onclick="openCity(event, 'classrooms')">Classrooms</button>
						<button class="tablinks locationss" onclick="openCity(event, 'locations')">Locations</button>
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
					<div id="users">
						<div class="userInfo">
							<input type="text" id="manageId" class="manageForm manageUser" placeholder="Id">
							<input type="text" id="manageFirstName" class="manageForm" placeholder="First Name">
							<input type="text" id="manageLastName" class="manageForm" placeholder="Last Name">
							<input type="text" id="manageEmail" class="manageForm" placeholder="Email">
							<select id="manageRole" class="manageForm">
							 <option value="" disabled selected hidden>Role</option>
								<option>Student</option>
								<option>Teacher</option>
								<option>Student counselor</option>
								<option>Teamleader</option>
							</select>
							<select id="manageFaculty" class="manageForm">
								<option value="" disabled selected hidden>Faculty</option>
								<option>HBO-ICT</option>
								<option>PABO</option>
								<option>Engineering</option>
							</select>
							<select id="manageClass" class="manageForm">
								<option value="" disabled selected hidden>Class</option>
								<option>WFHBOICT.V1A</option>
								<option>WFHBOICT.V1B</option>
								<option>WFHBOICT.V1C</option>
								<option>WFHBOICT.V1D</option>
								<option>WFHBOICT.V1E</option>
								<option>WFHBOICT.V1F</option>
							</select>
							<select id="manageComaker" class="manageForm">
								<option value="" disabled selected hidden>Year</option>
								<option>1st year</option>
								<option>2nd year</option>
								<option>3th year</option>
								<option>4th year</option>
							</select>
							<textarea id="manageNotes" class="manageForm" placeholder="Notes"></textarea>
							
							<input type="submit" id="buttonManageUser" class="button" value="Add user">

						</div>
					</div>

					<div id="classrooms"> 
						<div class="classroomInfo">
							<input type="text" id="classroomCode" class="manageForm" placeholder="Code">
							<select id="classroomLocation" class="manageForm">
								<option value="" disabled selected hidden>Location</option>
								<option>Windesheim Flevoland</option>
								<option>Alnovum</option>
								<option>Nieuwe bibliotheek</option>
							</select>
							<input type="submit" id="buttonManageClassroom" class="button" value="Add classroom">
						</div>
					</div>

					<div id="locations">
						<div class="classroomInfo">
							<input type="text" id="locationName" class="manageForm" placeholder="Locatie">
							<input type="text" id="ipPrefix" class="manageForm" placeholder="Ip prefix">
							<input type="submit" id="buttonManageLocation" class="button" value="Add location">
						</div>
						
					</div>
			</div>
	</div>
			<div id="helper2">
				<img class="lijn lijnManage" src="view/images/lijn.png"></img>
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
	
	// user form stuff
	var manageId = document.getElementById("manageId");
	var manageFirstName = document.getElementById("manageFirstName");
	var manageLastName = document.getElementById("manageLastName");
	var manageEmail = document.getElementById("manageEmail");
	var manageRole = document.getElementById("manageRole");
	var manageFaculty = document.getElementById("manageFaculty");
	var manageClass = document.getElementById("manageClass");
	var manageComaker = document.getElementById("manageComaker");
	var manageNotes = document.getElementById("manageNotes");
	var buttonManageUser = document.getElementById("buttonManageUser");
	// classroom form stuff
	var classroomCode = document.getElementById("classroomCode");
	var classroomLocation = document.getElementById("classroomLocation");
	var buttonManageClassroom = document.getElementById("buttonManageClassroom");
	// location form stuff
	var locationName = document.getElementById("locationName");
	var ipPrefix = document.getElementById("ipPrefix");
	var buttonManageLocation = document.getElementById("buttonManageLocation");
	
	var manageUser = document.getElementsByClassName(".manageUser");
		
	document.getElementById("filter").addEventListener("click", function(){

		
		$(document).ready(function(){
			if ( $('button.tablinks.userr').hasClass('active') ) {

				if (manageId.style.display === "block" && manageFirstName.style.display === "block" && manageLastName.style.display === "block" && manageEmail.style.display === "block" && manageRole.style.display === "block" && manageFaculty.style.display === "block" && manageClass.style.display === "block" && manageComaker.style.display === "block" && manageNotes.style.display === "block" && buttonManageUser.style.display === "block" && buttonManageUser.style.display === "block") {
					manageId.style.display = "none";
					manageFirstName.style.display = "none";
					manageLastName.style.display = "none";
					manageEmail.style.display = "none";
					manageRole.style.display = "none";
					manageFaculty.style.display = "none";
					manageClass.style.display = "none";
					manageComaker.style.display = "none";
					manageNotes.style.display = "none";
					buttonManageUser.style.display = "none";

				} else {
					manageId.style.display = "block";
					manageFirstName.style.display = "block";
					manageLastName.style.display = "block";
					manageEmail.style.display = "block";
					manageRole.style.display = "block";
					manageFaculty.style.display = "block";
					manageClass.style.display = "block";
					manageComaker.style.display = "block";
					manageNotes.style.display = "block";
					buttonManageUser.style.display = "block";
				}

			 }
		});

		$(document).ready(function(){
			if ( !$('button.tablinks.userr').hasClass('active') ) {	
				manageId.style.display = "none";
				manageFirstName.style.display = "none";
				manageLastName.style.display = "none";
				manageEmail.style.display = "none";
				manageRole.style.display = "none";
				manageFaculty.style.display = "none";
				manageClass.style.display = "none";
				manageComaker.style.display = "none";
				manageNotes.style.display = "none";
				buttonManageUser.style.display = "none";
			}
		});

		$(document).ready(function(){
			if ( $('button.tablinks.classroomss').hasClass('active') ) {

				if (classroomCode.style.display === "block" && classroomLocation.style.display === "block" && buttonManageClassroom.style.display === "block" ) {
					classroomCode.style.display = "none";
					classroomLocation.style.display = "none";
					buttonManageClassroom.style.display = "none";
				} else {
					classroomCode.style.display = "block";
					classroomLocation.style.display = "block";
					buttonManageClassroom.style.display = "block";

				}
			}
		});	

		$(document).ready(function(){
			if ( !$('button.tablinks.classroomss').hasClass('active') ) {	
					classroomCode.style.display = "none";
					classroomLocation.style.display = "none";
					buttonManageClassroom.style.display = "none";
			}
		});	

		$(document).ready(function(){
			if ( $('button.tablinks.locationss').hasClass('active') ) {

				if (locationName.style.display === "block" && ipPrefix.style.display === "block" && buttonManageLocation.style.display === "block" ) {
					locationName.style.display = "none";
					ipPrefix.style.display = "none";
					buttonManageLocation.style.display = "none";
				} else {
					locationName.style.display = "block";
					ipPrefix.style.display = "block";
					buttonManageLocation.style.display = "block";
				}
			}
		});	

		$(document).ready(function(){
			if ( !$('button.tablinks.locationss').hasClass('active') ) {	
				locationName.style.display = "none";
				ipPrefix.style.display = "none";
				buttonManageLocation.style.display = "none";
			}
		});
		
		
	});
	
	
</script>	
	

