<?php

namespace Team10\Absence\View;
use Team10\Absence\Model\User as User;
use Team10\Absence\Model\ClassObj as ClassObj;
use Team10\Absence\Model\Course as Course;

if (isset($_GET["type"])) {
	$type = $_GET["type"];
	
	switch ($type) {
		case "students":
			if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new User($_GET["id"]);
					$objectRoleId = $object->getRole();

					if ($objectRoleId == 1) {
						$objectName = $object->getFirstname() . " " . $object->getLastname();
						$set = true;
					} else {
						$set = false;
					}
				} else {
					$set = false;
				}
			} else {
				$set = false;
			}
			break;
		case "teachers":
			if ($userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new User($_GET["id"]);
					$objectRoleId = $object->getRole();

					if ($objectRoleId != 1) {
						$objectName = $object->getFirstname() . " " . $object->getLastname();
						$set = true;
					} else {
						$set = false;
					}
				} else {
					$set = false;
				}
			} else {
				$set = false;
			}
			break;
		case "classes":
			if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new ClassObj($_GET["id"]);
					$objectName = $object->getCode();

					if ($objectName) {
						$set = true;
					} else {
						$set = false;
					}
				} else {
					$set = false;
				}
			} else {
				$set = false;
			}
			break;
		case "courses":
			if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new Course($_GET["id"]);
					$objectName = $object->getName();

					if ($objectName) {
						$set = true;
					} else {
						$set = false;
					}
				} else {
					$set = false;
				}
			} else {
				$set = false;
			}
			break;
		default:
			$set = false;
	}
} else {
	$set = false;
}

?>
<style>

</style>

<?php if ($userRole != 1) { ?>
<div id="searchBar">
	<div id="filter">
		<i class="fa fa-sliders" aria-hidden="true"></i>
	</div>
	<input id="searchBox" type="text" placeholder="Search..." <?php if ($set) { echo "value='" . $objectName . "'"; } ?> />
	<div id="searchButton" onclick="search();">
		<i class="fa fa-search" aria-hidden="true"></i>
	</div>
	<div id="results">

	</div>
</div>

<script type="text/javascript">			
	function search() {
		var search = document.getElementById("searchBox").value;
		if (search == "" || search == null) {
			document.getElementById("results").innerHTML = "";
		} else {
			var http = new XMLHttpRequest();
			var params = "search=" + search;

			http.open("POST", "<?php echo PROTOCOL . DOMAIN . ROOT; ?>webClient", true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					var results = JSON.parse(this.responseText);
					var html = "";
					var totalCount = 0;
					
					if (typeof results.students != "undefined") {
						var count = 0;
						for (var result in results.students) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=students&id=" + results.students[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.students[result].id + ")</i> " + results.students[result].firstname + " " + results.students[result].lastname + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.teachers != "undefined") {
						var count = 0;
						for (var result in results.teachers) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=teachers&id=" + results.teachers[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.teachers[result].id + ")</i> " + results.teachers[result].firstname + " " + results.teachers[result].lastname + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.courses != "undefined") {
						var count = 0;
						for (var result in results.courses) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=courses&id=" + results.courses[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.courses[result].code + ")</i> " + results.courses[result].name + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.classes != "undefined") {
						var count = 0;
						for (var result in results.classes) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=classes&id=" + results.classes[result].id + "'><div class='result result-" + totalCount + "'>" + results.classes[result].code + "</div></a>";
							totalCount++;
						}
					}

					document.getElementById("results").innerHTML = html;
				} 
			};
			http.send(params);
		}
	}

	document.getElementById("searchBox").onkeyup = function () {
		search();
	};
</script>
<?php  
	}
	if ((isset($noData) && $noData === true)) {
?>	

nodata






<?php } elseif (!$set) { ?>

niks geselecteerd
<?php } else { ?>

wel data
<?php } ?>