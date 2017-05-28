<?php

namespace Team10\Absence\View;
use Team10\Absence\Model\User as User;
use Team10\Absence\Model\ClassObj as ClassObj;
use Team10\Absence\Model\Course as Course;
use Team10\Absence\Model\Connection as Connection;



if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
	if (isset($_GET["id"])) {
		$object = new Course($_GET["id"]);
		$objectName = $object->getName();
	}
}

function addCourse() {
	echo "hoi";
}


	
//$result = $connection->query("SELECT id FROM classes WHERE code = '" . $class . "'");
//
//		if (!$result) {
//			$id = $connection->query("INSERT INTO classes (code) VALUES ('" . $class . "')", "insert");
//		}

?>


<div class="containerManage">

	<div class="columnLeftManage">
		<div class="columnLeftManage2">
		<form id="currentCourseForm">
			<div class="importCourse">Course imported from schedule:</div><br /><br />
			<div class="course"><?php if ($objectName != "") { echo $objectName;} else {echo "No Course Selected";} ?></div>
			<div class="startCourse"><button onclick="startCourse()" class="button"><i class="fa fa-play" aria-hidden="true"></i></button></div>
			<input id="currentCourseInput" type="hidden" name="courseId" value="<?php if (isset($_GET["id"])) { echo $_GET["id"]; } ?>">
		</form>
		</div>
		</div>

		<div class="columnRightManage">
			<div class="columnRightManage2">
					Find or create a course:
					<div id="searchBar">
						<div id="filter">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</div>
						<input id="searchBox" class="manageForm" type="text" placeholder="Search..." />
							<div id="searchButton" onclick="search();">
								<i class="fa fa-search" aria-hidden="true"></i>
							</div>
							<div id="resultsManage">
								
							</div>
							<input type="text" id="newCourseName" class="manageForm" placeholder="Name">
							<input type="text" id="newCourseCode" class="manageForm" placeholder="Code">
							<input type="submit" class="button buttonAddCourse" value="Add course">
	
				</div>
			</div>

		</div>	
			<div id="helper2">
				<img class="lijn lijnNewCourse" src="view/images/lijn.png"></img>
			</div>
</div>

<script>
	function search() {
		var search = document.getElementById("searchBox").value;
		if (search == "" || search == null) {
			document.getElementById("resultsManage").innerHTML = "";
		} else {
			var http = new XMLHttpRequest();
			var params = "search=" + search + "&type=courses";

			http.open("POST", "<?php echo PROTOCOL . DOMAIN . ROOT; ?>webClient", true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					var results = JSON.parse(this.responseText);
					var html = "";
					var totalCount = 0;
					
					if (typeof results.courses != "undefined") {
						var count = 0;
						for (var result in results.courses) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/lesson?id=" + results.courses[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.courses[result].code + ")</i> " + results.courses[result].name + "</div></a>";
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
	
	var newCourseName = document.getElementById("newCourseName");
	var newCourseCode = document.getElementById("newCourseCode");
	var addCourse = document.querySelector(".buttonAddCourse");
	
	document.getElementById("filter").addEventListener("click", function(){

		if (newCourseName.style.display === "block" && newCourseCode.style.display === "block" && addCourse.style.display === "block") {
 			newCourseName.style.display = "none";
 			newCourseCode.style.display = "none";
 			addCourse.style.display = "none";
		} else {
			newCourseName.style.display = "block";
			newCourseCode.style.display = "block";
			addCourse.style.display = "block";
		}
	});
	
	document.querySelector(".buttonAddCourse").addEventListener("click", function(){
		if (newCourseName.value !== "" && newCourseCode.value !== "") {
			addCourse();
		}
		
	});

	
	function startCourse () {
		var input = document.getElementById("currentCourseInput").value;
		if (input != "" && input != null && input != undefined) {
			document.getElementById("currentCourseForm").submit;	
		} else {
			alert("error");
		}

	}
	
	
	
	
</script>


	