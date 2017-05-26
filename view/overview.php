<?php

namespace Team10\Absence\View;
use Team10\Absence\Model\User as User;
use Team10\Absence\Model\ClassObj as ClassObj;
use Team10\Absence\Model\Course as Course;
use Team10\Absence\Model\Connection as Connection;

function getProfilePicture($id) {
	$idTry = str_replace("s", "", $id);
	if (is_int((int) $idTry)) {
		$id = $idTry;
	}
	$url = PROTOCOL . DOMAIN . ROOT . "view/images/profilePictures/" . $id . ".jpg";
	$ch = curl_init($url);    
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($code == 200) {
		$status = $url;
	} else {
		$status = false;
	}
	curl_close($ch);
	return $status;
}

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
						$objectType = "Student";
						$objectId = $_GET["id"];
						$objectClass = $object->getClass();
						$objectComakership = $object->getComakership();
						$objectNotes = $object->getNotes();
						$objectIcon = "graduation-cap";
						$objectProfilePicture = getProfilePicture($objectId);

						if (!isset($_GET["filter"])) {
							$results = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM storedPresence WHERE userId = '" . $objectId . "'");
							if ($results !== false) { 
								if (isset($results["present"])) {
									$result = $results;
									unset($results);
									$results = [0 => $result];
								}
								$present = 0;
								$late = 0;
								$absent = 0;


								foreach ($results as $result) {
									if ($result["present"] == 1) {
										$present++;
									} elseif ($result["present"] == 2) {
										$late++;
									} else {
										$absent++;
									}
								}
								$present = [$present];
								$late = [$late];
								$absent = [$absent];
								$labels2 = json_encode(["Alltime"]);
								$set = true;
							} else {
								$noData = true;
							}
						}
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
						$objectType = "Teacher";
						$objectId = $_GET["id"];
						$objectIcon = "user";
						$objectProfilePicture = getProfilePicture($objectId);
						
						if (!isset($_GET["filter"])) {
							$results = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM storedPresence WHERE teacherId = '" . $objectId . "'");
							
							if ($results !== false) {
								if (isset($results["present"])) {
									$results2 = [0 => $results];
								} else {
									$results2 = $results;
								}
								
								$years = [];
								$ir = "";
								
								foreach ($results2 as $result) {
									$courses = [];
									$course = (new Course($result["courseId"]))->getName();
									$teacher = (new User($result["teacherId"]))->getFirstname() . " " . (new User($result["teacherId"]))->getLastname();
									foreach ($courses as $cours) {
										if (in_array($course, $cours) && in_array($teacher, $cours)) {
											$add = false;
										}
									}
									
									if (!isset($add) && $add !== false) {
										$courses[] = [$course, $teacher];
										unset($add);
									}
									
									$year = date_parse($result["date"]);
									$year =  $year["year"];
									
									foreach ($years as $yea) {
										if (in_array($year, $yea)) {
											$add = false;
										}
									}
									
									if (!isset($add) || $add !== false) {
										$years[] = [$year, $courses];
									}
									
									unset($courses);
								}
								
								//var_dump($years);
								
								$num = 1;
								foreach ($years as $year) {
									$ir .= "<div id='recordContainer" . $num . "' class='recordContainer expand' onClick='collapse(" . $num . ");'><b>Year</b> - ";
									$ir .= $year[0];
									$ir .= "</div>";
									$ir .= "<div id='recordInner" . $num . "' class='recordInner'>";
									$num++;
									$ir .= "<div id='recordContainer" . $num . "' class='recordContainer expand' onClick='collapse(" . $num . ");'><b>" . $year[1][0][0] . "</b> - " . $year[1][0][1] . "</div><div id='recordInner" . $num . "' class='recordInner'>inner</div></div>";
									$num++;
								}
								//var_dump($years);
							}
							$set = true;
						}
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
						$objectType = "Class";
						$objectIcon = "users";
						
						if (!isset($_GET["filter"])) {
							$results = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM storedPresence WHERE classId = '" . $_GET["id"] . "'");
							$set = true;
						}
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
						$objectType = "Course";
						$objectId = $object->getCode();
						$objectIcon = "book";
						
						if (!isset($_GET["filter"])) {
							$results = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM storedPresence WHERE courseId = '" . $_GET["id"] . "'");
							$set = true;
						}
					}
				}
			}
			break;
	}
}

if (!isset($set)) {
	$set = false;
} else {
	if ($results !== false) { 
		if (isset($results["present"])) {
			$result = $results;
			unset($results);
			$results = [0 => $result];
		}
		
		$present = 0;
		$late = 0;
		$absent = 0;


		foreach ($results as $result) {
			if ($result["present"] == 1) {
				$present++;
			} elseif ($result["present"] == 2) {
				$late++;
			} else {
				$absent++;
			}
		}

		$total = $present + $late + $absent;
		$percentage = [
			"present" => str_replace(".", ",", (string) round((($present / $total) * 100), 1)),
			"late" => str_replace(".", ",", (string) round((($late / $total) * 100), 1)),
			"absent" => str_replace(".", ",", (string) round((($absent / $total) * 100), 1))
		];

		$present = [$present];
		$late = [$late];
		$absent = [$absent];
		$labels2 = json_encode(["Alltime"]);
	} else {
		$noData = true;
	}
}


$labels = ["Present", "Late", "Absent"];
$data = [$present, $late, $absent];

// Code for graph

function generateDatasets($data, $labels) {
	$count = 0;
	$echo = "";
	foreach ($data as $dataset) {
		if ($count == 0) {
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(75, 176, 1, 0.3)', borderColor: 'rgba(75, 176, 1, 1)', borderWidth: 1 },";
		} elseif ($count == 1) {
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(176, 137, 1, 0.3)', borderColor: 'rgba(176, 137, 1, 1)', borderWidth: 1 },";
		} else {
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(176, 34, 1, 0.3)', borderColor: 'rgba(176, 34, 1, 1)', borderWidth: 1 },";
		}
		$count++;
	}
	return $echo;
}

$echo = generateDatasets($data, $labels);

?>
<style>
	* {
		cursor: default;
	}
	
	input[type="text"], input[type="password"], textarea {
		cursor: text;
	}
	
	a, button, input[type="submit"], *[href], *[onclick], *[href] * , *[onclick] *, a * {
		cursor: pointer;
	}
	
	.noData {
		color: #cccccc;
		position: absolute;
		display: block;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		font-size: 80px;
	}
	
	.row {
		width: 100%;
		display: block;
		margin: 15px auto;
		text-align: left;
	}
	
	.row1 {
		max-height: 354px;
		min-height: 354px;
	}
	
	.columnLeft {
		width: calc(65% - 97px);
		margin-right: 27px;
		height: 250px;
		background-color: #f9fcfd;
   		box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 52px 45px;
		float: left;
	}
	
	.columnRight {
		width: calc(35% - 110px);
		height: 250px;
    	box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 52px 45px;
		float: left;
		background: #036992; /* Old browsers */
		background: -moz-linear-gradient(left, #036992 0%, #0577a4 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(left, #036992 0%,#0577a4 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to right, #036992 0%,#0577a4 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#036992', endColorstr='#0577a4',GradientType=1 ); /* IE6-9 */
	}

	.columnNone {
		width: calc(100% - 90px);
		height: auto;
		background-color: #f9fcfd;
   		box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.31);
		padding: 52px 45px;
		background: #247c9f;
		background: -moz-linear-gradient(left, #247c9f 0%, #0ba2dd 100%);
		background: -webkit-linear-gradient(left, #247c9f 0%,#0ba2dd 100%);
		background: linear-gradient(to right, #247c9f 0%,#0ba2dd 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#247c9f', endColorstr='#0ba2dd',GradientType=1 );
	}
	
	.row .columnRight h2.title, .row .columnNone h2.title {
		font-size: 33px;
		color: #ffffff;
		display: block;
		line-height: 1.2;
	}
	
	.columnRight h3.name {
		font-size: 21px;
    	color: #ffffff;
		line-height: 0.9;
    	margin-bottom: 4px;
		width: calc(100% - 85px);
	}
	
	.columnRight h3.id {
		font-size: 16px;
    	color: #ffffff;
		font-style: italic;
		line-height: 1.2;
	}
	
	.columnRight h3.class, .columnRight h3.comakership, .columnRight h3.notes {
		font-size: 16px;
    	color: #ffffff;
		line-height: 1.4;
	}
	
	.columnRight h3.class b, .columnRight h3.comakership b, .columnRight h3.notes b {
		font-weight: bold;
		display: inline-block;
	}
	
	.columnRight h3.class .content, .columnRight h3.comakership .content {
		display: inline-block;
	}
	
	.columnRight h3.class {
		margin-top: 22px;
	}
	
	.columnRight h3 .content {
		line-height: 0.9;
		font-style: italic;
	}
	
	.columnRight .typeException {
		top: 50%;
		position: relative;
		left: 50%;
		transform: translate(-50%, -50%);
	}
	
	.overviewIcon {
		font-family: FontAwesome !important;
		color: #ffffff;
		font-size: 80px;
		float: right;
		margin-top: -80px;
	}
	
	.profilePictureContainer {
		width: 120px;
		height: 120px;
		float: right;
		margin-top: -80px;
		overflow: hidden;
		border-radius: 50%;
	}
	
	.profilePictureContainer .overviewProfilePicture {
		width: 100%;
		height: auto;
		margin-top: -15%;
	}
	
	#searchBar #results {
    	width: calc(100% - 108px);
	}
	
	#searchBar {
		z-index: 2;
    	position: relative;
	}
	
	#mainChart {
		width: 90% !important;
		height: auto !important;
		margin: -15px auto 0;
		display: block;
		float: right;
		position: relative;
		right: 13px;
		z-index: 0;
	}
	
	.switch + #mainChart {
		top: -10px;
	}
	
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
		margin-left: calc(100% - 60px);
		margin-top: -20px;
		z-index: 1;
	}

	/* Hide default HTML checkbox */
	.switch input {
		display:none;
	}

	/* The slider */
	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #015679;
		-webkit-transition: 0.4s;
		transition: 0.4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: 0.4s;
		transition: 0.4s;
	}

	input:checked + .slider {
		background-color: #0ba2dd;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #0ba2dd;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
	
	#percentageContainer {
		width: 100%;
		margin-top: 40px;
	}
	
	.profilePictureContainer + #percentageContainer {
		margin-top: 60px;
	}
	
	#percentageContainer .present, #percentageContainer .late, #percentageContainer .absent {
		width: calc(100% / 3 - 40px);
		padding: 10px;
		display: inline-block;
		color: #ffffff;
		font-size: 100%;
		height: auto;
		border-radius: 3px;
		text-align: center;
	}
	
	#percentageContainer .present {
		background-color: #4bb001;
	}
	
	#percentageContainer .late {
		background-color: #b08901;
		margin-left: 30px;
	}
	
	#percentageContainer .absent {
		background-color: #b02201;
		margin-left: 30px;
	}
	
	.noDataRecorded {
		color: #cccccc;
		position: relative;
		display: block;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		font-size: 80px;
	}
	
	.recordsBox {
		width: 100%;
		min-height: 300px;
		height: auto;
		border-top: 2px solid #ffffff;
		border-bottom: 2px solid #ffffff;
		position: relative;
		display: block;
		margin-top: 10px;
	}
	
	.recordsBox .noDataRecorded {
		color: #096C92;
		position: absolute;
	}
	
	.nicescroll-rails {
		z-index: 5 !important;
	}
	
	::selection {
		background: #0ba2dd;
		color: #ffffff;
	}
	::-moz-selection {
		background: #0ba2dd;
		color: #ffffff;
	}
	
	.recordContainer {
		padding:  13px 20px;
		color: #ffffff;
		border-bottom: 1px solid #ffffff;
		border-top: 1px solid #ffffff;
		width: calc(100% - 40px);
		font-size: 17px;
		font-style: italic;
		margin-top: -1px;
	}
	
	.recordContainer b {
		font-weight: bold;
		font-style: normal;
	}
	
	main {
		overflow-x: hidden;
	}
	
	.recordInner {
		width: calc(100% - 40px);
		margin-left: 40px;
		display: none;
	}
	
	.recordInner.expanded {
		display: block;
	}
</style>
<?php if ($userRole != 1) { ?>
<div id="searchBar">
	<div id="filter" onclick="">
		<i class="fa fa-sliders" aria-hidden="true"></i>
	</div>
	<input id="searchBox" type="text" placeholder="Search..." <?php if ($set || $noData === true) { echo "value='" . $objectName . "'"; } ?> />
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
		<div class="row row1">
			<div class="columnLeft">
				<div class="noDataRecorded">No data</div>
			</div>
			<div class="columnRight">
				<?php if ($objectType != "Student") { echo "<div class='typeException'>"; } ?>
				<h2 class="title"><?php echo $objectType; ?></h2>
				<h3 class="name"><?php echo $objectName; ?></h3>
				<?php if ($objectType != "Class") { ?>
				<h3 class="id">(<?php echo $objectId; ?>)</h3>
				<?php } ?>
				<?php if (isset($objectProfilePicture) && $objectProfilePicture !== false) { ?>
				<div class="profilePictureContainer"><img src="<?php echo $objectProfilePicture; ?>" class="overviewProfilePicture" /></div>
				<?php } else { ?>
				<i class="fa fa-<?php echo $objectIcon; ?> overviewIcon"></i>
				<?php } ?>
				<?php if ($objectType == "Student") { ?>
				<h3 class="class"><b>Class:</b> <div class='content'><?php echo $objectClass; ?></div></h3>
				<h3 class="comakership"><b>Comakership:</b> <div class='content'><?php echo $objectComakership; ?></div></h3>
				<h3 class="notes"><b>Notes:</b><div class='content'><?php echo $objectNotes; ?></div></h3>			
				<?php } else { ?>
				<div id="percentageContainer"><div class="present">N/a</div><div class="late">N/a</div><div class="absent">N/a</div></div>
				<?php } ?>
				<?php if ($objectType != "Student") { echo "</div>"; } ?>
			</div>
		</div>
		<div class="row">
			<div class="columnNone">
				<h2 class="title">Individual Records</h2>
				<div class="recordsBox"><div class="noDataRecorded">No data</div></div>
			</div>
		</div>
<?php } elseif (!$set) { ?>
		<p class="noData">No object selected</p>
<?php } else { ?>
		<div class="row row1">
			<div class="columnLeft">
				<?php if (isset($filterType)) { ?>
				<label class="switch">
					<input id="switch" type="checkbox">
					<div class="slider round"></div>
				</label>
				<?php } ?>
				<canvas id="mainChart" width="900" height="400"></canvas>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
				<script>
					// Draw Graph
					var labels = <?php echo $labels2; ?>;
					var ctx = document.getElementById("mainChart");
					var count = 0;
					
					function drawBarChart(ctx) {
						var mainChart = Chart.Bar(ctx, {
							data: {
								labels: labels,
								datasets: [<?php echo $echo; ?>]
							},
							options: {
								legend: {labels:{fontColor:"#464648"}},
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true,
											fontColor: "#464648"
										},
										scaleLabel: {
											display: true,
											labelString: 'Lessons',
											fontColor: "#464648"
										}, gridLines : {
											display : false
										}
									}],
									xAxes: [{
										ticks: {
											fontColor: "#464648"
										}, gridLines : {
											display : false
										}
									}]
								}
							}
						});
						
						return mainChart;
					}
					
					function drawLineChart(ctx) {
						var mainChart = Chart.Line(ctx, {
							data: {
								labels: labels,
								datasets: [<?php echo $echo; ?>]
							},
							options: {
								legend: {labels:{fontColor:"#464648"}},
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true,
											fontColor: "#464648"
										},
										scaleLabel: {
											display: true,
											labelString: 'Lessons',
											fontColor: "#464648"
										}, gridLines : {
											display : false
										}
									}],
									xAxes: [{
										ticks: {
											fontColor: "#464648"
										}, gridLines : {
											display : false
										}
									}]
								}
							}
						});
						
						return mainChart;
					}
					
					var mainChart = drawBarChart(ctx);
					
					function switchGraph(mainChart) {
						mainChart.destroy();
						if (count === 0) {
							mainChart = drawLineChart(ctx);
							count++;
						} else {
							mainChart = drawBarChart(ctx);
							count--;
						}
						
						return mainChart;
					}
					
					<?php if (isset($filterType)) { ?>
					document.getElementById("switch").addEventListener("click", function() {
						mainChart = switchGraph(mainChart);
					}, false);
					<?php } ?>
					
					function collapse(id) {
						var inner = "recordInner" + id;
						document.getElementById(inner).classList.toggle("expanded");
					}
				</script>
			</div>
			<div class="columnRight">
				<?php if ($objectType != "Student") { echo "<div class='typeException'>"; } ?>
				<h2 class="title"><?php echo $objectType; ?></h2>
				<h3 class="name"><?php echo $objectName; ?></h3>
				<?php if ($objectType != "Class") { ?>
				<h3 class="id">(<?php echo $objectId; ?>)</h3>
				<?php } ?>
				<?php if (isset($objectProfilePicture) && $objectProfilePicture !== false) { ?>
				<div class="profilePictureContainer"><img src="<?php echo $objectProfilePicture; ?>" class="overviewProfilePicture" /></div>
				<?php } else { ?>
				<i class="fa fa-<?php echo $objectIcon; ?> overviewIcon"></i>
				<?php } ?>
				<?php if ($objectType == "Student") { ?>
				<h3 class="class"><b>Class:</b> <div class='content'><?php echo $objectClass; ?></div></h3>
				<h3 class="comakership"><b>Comakership:</b> <div class='content'><?php echo $objectComakership; ?></div></h3>
				<h3 class="notes"><b>Notes:</b><div class='content'><?php echo $objectNotes; ?></div></h3>			
				<?php } else { ?>
				<div id="percentageContainer"><div class="present"><?php echo $percentage["present"]; ?>%</div><div class="late"><?php echo $percentage["late"]; ?>%</div><div class="absent"><?php echo $percentage["absent"]; ?>%</div></div>
				<?php } ?>
				<?php if ($objectType != "Student") { echo "</div>"; } ?>
			</div>
		</div>
		<div class="row">
			<div class="columnNone">
				<h2 class="title">Individual Records</h2>
				<div class="recordsBox">
					<?php echo $ir; ?>
				</div>
			</div>
		</div>
<?php } ?>
