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
					} 
				}
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
					}
				}
			}
			break;
		case "classes":
			if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new ClassObj($_GET["id"]);
					$objectName = $object->getCode();

					if ($objectName) {
						$set = true;
					}
				}
			}
			break;
		case "courses":
			if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
				if (isset($_GET["id"])) {
					$object = new Course($_GET["id"]);
					$objectName = $object->getName();

					if ($objectName) {
						$set = true;
					}
				}
			}
			break;
	}
}

if (!isset($set)) {
	$set = false;
}

?>
<style>
	.noData {
		color: #464648;
		font-size: 20px;
		margin-top: 65px;
	}
	
	.row {
		width: 100%;
		display: block;
		margin: 15px auto;
		text-align: left;
	}
	
	.columnLeft {
		display: inline-block;
		width: calc(65% - 92px);
		margin-right: 27px;
		height: 300px;
		background-color: #f9fcfd;
   		box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 40px;
	}
	
	.columnRight {
		display: inline-block;
		width: calc(35% - 100px);
		height: 300px;
    	box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 40px;
		background: #036992; /* Old browsers */
		background: -moz-linear-gradient(left, #036992 0%, #0577a4 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(left, #036992 0%,#0577a4 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to right, #036992 0%,#0577a4 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#036992', endColorstr='#0577a4',GradientType=1 ); /* IE6-9 */
	}

	.columnNone {
		width: calc(100% - 80px);
		height: auto;
		background-color: #f9fcfd;
   		box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 40px;
		background: #247c9f;
		background: -moz-linear-gradient(left, #247c9f 0%, #0ba2dd 100%);
		background: -webkit-linear-gradient(left, #247c9f 0%,#0ba2dd 100%);
		background: linear-gradient(to right, #247c9f 0%,#0ba2dd 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#247c9f', endColorstr='#0ba2dd',GradientType=1 );
	}
}

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
							if (count >= 2) {
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
							if (count >= 2) {
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
							if (count >= 2) {
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
							if (count >= 2) {
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
		<p class="noData">No data for selected object</p>
<?php } elseif (!$set) { ?>
		<p class="noData">No object selected</p>
<?php } else { ?>
		<div class="row">
			<div class="columnLeft">
				1
			</div>
			<div class="columnRight">
				2
			</div>
		</div>
		<div class="row">
			<div class="columnNone">
				2
			</div>
		</div>
<?php } ?>