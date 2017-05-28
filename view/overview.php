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

function getDoughnutGraph($data, $id) {
	$present = 0;
	$late = 0;
	$absent = 0;
	foreach ($data as $record) {
		if ($record[1] == 1) {
			$present++;
		} elseif ($record[1] == 2) {
			$late++;
		} elseif ($record[1] == 3) {
			$absent++;
		}
	}
	$data = json_encode([$present, $late, $absent]);
	$graph = "<canvas id='chart" . $id . "' class='innerChart' width='1600' height='400'></canvas>"; 
	$graph .= "<script>
		var data = {
			labels: ['Present', 'Late', 'Absent'],
			datasets: [
				{
					data: " . $data . ",
					backgroundColor: [
						'#4bb001',
						'#b08901',
						'#b02201'
					],
					hoverBackgroundColor: [
						'#307030',
						'#705a30',
						'#703030'
					]
				}]
		};
		var ctx = document.getElementById('chart" . $id . "');
		var myDoughnutChart = new Chart(ctx, {
			type: 'doughnut',
			data: data,
			options: {
				animation: {
				animateScale: true
			}
			}
		});
	</script>";
	return [$graph, $present, $late, $absent];
}


function getIndividualRecords($years) {
	$ir = "";
	$num = 1;
	if (isset($years[1])) {
		foreach ($years as $year) {
			$ir .= "<div id='recordContainer" . $num . "' class='recordContainer' onClick='collapse(" . $num . ");'><b>Year</b> - ";
			$ir .= $year[0];
			$ir .= "</div>";
			$ir .= "<div id='recordInner" . $num . "' class='recordInner'>";
			foreach ($year[1] as $course) {
				$num++;
				$ir .= "<div id='recordContainer" . $num . "' class='recordContainer' onClick='collapse(" . $num . ");'><b>" . $course[0] . "</b> - " . $course[1] . " <a href='" . PROTOCOL . DOMAIN . ROOT . "/overview?type=courses&id=" . $course[3] . "' target='_blank' class='externalLink'><i class='fa fa-external-link' aria-hidden='true'></i></a></div>";
				$ir .= "<div id='recordInner" . $num . "' class='recordInner'>";
				foreach ($course[2] as $lesson) {
					$num++;
					$ir .= "<div id='recordContainer" . $num . "' class='recordContainer' onClick='collapse(" . $num . ");'><b>Lesson " . $lesson[0] . "</b> - " . $lesson[1] . "</div>";
					$graph = getDoughnutGraph($lesson[2], $num);
					$ir .= "<div class='absoluteRecords'><div class='present'>" . $graph[1] . "</div><div class='late'>" . $graph[2] . "</div><div class='absent'>" . $graph[3] . "</div></div>";
					$ir .= "<div id='recordInner" . $num . "' class='recordInnerOverview'>";
					$ir .= $graph[0];
					$ir .= "<div class='individualRecordsContainer'>";
					$ir .= "<h2>Students</h2>";
					foreach ($lesson[2] as $student) {
						$ir .= "<div class='row'><a href='" . PROTOCOL . DOMAIN . ROOT . "/overview?type=students&id=" . $student[2] . "' target='_blank'>" . $student[0] . " <i class='fa fa-external-link' aria-hidden='true'></i></a>";
						if ($student[1] == 1) {
							$ir .= "<div class='present'>Present</div>";
						} elseif ($student[1] == 2) {
							$ir .= "<div class='late'>Late</div>";
						} elseif ($student[1] == 3) {
							$ir .= "<div class='absent'>Absent</div>";
						}
						$ir .= "</div>";
					}
					$ir .= "</div>";
					$ir .= "</div>";
				}
				$ir.="</div>";
			}
			$ir .= "</div>";
			$num++;
		}
	} else {
		foreach ($years[0][1] as $course) {
			$ir .= "<div id='recordContainer" . $num . "' class='recordContainer' onClick='collapse(" . $num . ");'><b>" . $course[0] . "</b> - " . $course[1] . " <a href='" . PROTOCOL . DOMAIN . ROOT . "/overview?type=courses&id=" . $course[3] . "' target='_blank' class='externalLink'><i class='fa fa-external-link' aria-hidden='true'></i></a></div>";
			$ir .= "<div id='recordInner" . $num . "' class='recordInner'>";
			foreach ($course[2] as $lesson) {
				$num++;
				$ir .= "<div id='recordContainer" . $num . "' class='recordContainer' onClick='collapse(" . $num . ");'><b>Lesson " . $lesson[0] . "</b> - " . $lesson[1] . "</div>";
				$graph = getDoughnutGraph($lesson[2], $num);
				$ir .= "<div class='absoluteRecords'><div class='present'>" . $graph[1] . "</div><div class='late'>" . $graph[2] . "</div><div class='absent'>" . $graph[3] . "</div></div>";
				$ir .= "<div id='recordInner" . $num . "' class='recordInnerOverview'>";
				$ir .= $graph[0];
				$ir .= "<div class='individualRecordsContainer'>";
				$ir .= "<h2>Students</h2>";
				foreach ($lesson[2] as $student) {
					$ir .= "<div class='row'><a href='" . PROTOCOL . DOMAIN . ROOT . "/overview?type=students&id=" . $student[2] . "' target='_blank'>" . $student[0] . " <i class='fa fa-external-link' aria-hidden='true'></i></a>";
					if ($student[1] == 1) {
						$ir .= "<div class='present'>Present</div>";
					} elseif ($student[1] == 2) {
						$ir .= "<div class='late'>Late</div>";
					} elseif ($student[1] == 3) {
						$ir .= "<div class='absent'>Absent</div>";
					}
					$ir .= "</div>";
				}
				$ir .= "</div>";
				$ir .= "</div>";
			}
			$ir .= "</div>";
			$num++;
		}
	}
	
	return $ir;
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
						$objectProfilePicture = $object->getProfilePicture();

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
						$objectProfilePicture = $object->getProfilePicture();
						
						if (!isset($_GET["filter"])) {
							$results = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT * FROM storedPresence WHERE teacherId = '" . $objectId . "' ORDER BY storedPresence.date DESC");
							
							if ($results !== false) {
								if (isset($results["present"])) {
									$results2 = [0 => $results];
								} else {
									$results2 = $results;
								}
								
								$years = [];
<<<<<<< HEAD
								foreach ($results2 as $result) {
									$year = date_parse($result["date"])["year"];
									
									if (!in_array($year, $years)) {
										$years[] = $year;
									}
								}
								
								foreach ($years as $year) {
									$yearsNew[] = [$year, []];
								}
								$years = $yearsNew;
								
								foreach ($results2 as $result) {
									$year = date_parse($result["date"])["year"];
									$count = 0;
									foreach ($years as $yearContainer) {
										if ($year == $yearContainer[0]) {
											$keyYear = $count;
										}
										$count++;
									}
									
									$course = (new Course($result["courseId"]))->getName();
									$comakership = (new User($result["userId"]))->getComakership();
									
									foreach($years[$keyYear][1] as $courses) {
										if ($courses[0] == $course) {
											$in = true;
										}
									}
									
									if (!isset($in) || $in !== true) {
										$lessons = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT storedPresence.sequence, storedPresence.teacherId FROM storedPresence, courses WHERE storedPresence.teacherId = '" . $objectId . "' AND courses.name = '" . $course . "' AND courses.id = storedPresence.courseId ORDER BY storedPresence.sequence ASC");
										if (isset($lessons["sequence"])) {
											$teacher = (new User($lessons["teacherId"]))->getFirstname() . " " . (new User($lessons["teacherId"]))->getLastname();
											$studentIds = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT storedPresence.userId, storedPresence.present FROM storedPresence, courses WHERE storedPresence.teacherId = '" . $objectId . "' AND courses.name = '" . $course . "' AND courses.id = storedPresence.courseId AND storedPresence.sequence = '" . $lessons["sequence"] . "'");
											$students = [];
											if (!isset($studentIds["userId"])) {
												foreach ($studentIds as $student) {
													$students[] = [(new User($student["userId"]))->getFirstname() . " " . (new User($student["userId"]))->getLastname(), $student["present"], $student["userId"]];
												}
											} else {
												$students[] = [(new User($studentIds["userId"]))->getFirstname() . " " . (new User($studentIds["userId"]))->getLastname(), $studentIds["present"], $studentIds["userId"]];
											}
											$years[$keyYear][1][] = [$course, $comakership, [[$lessons["sequence"], $teacher, $students]], $result["courseId"]];
											unset($students);
										} else {
											$allLessons = [];
											foreach ($lessons as $lesson) {
												foreach ($allLessons as $allLesson) {
													if ($allLesson[0] == $lesson["sequence"]) {
														$add = false;
													}
												}
												
												if (!isset($add) || $add !== false) {
													$studentIds = (new Connection(DBHOST, DBUSER, DBPASS, DBNAME))->query("SELECT storedPresence.userId, storedPresence.present FROM storedPresence, courses WHERE storedPresence.teacherId = '" . $objectId . "' AND courses.name = '" . $course . "' AND courses.id = storedPresence.courseId AND storedPresence.sequence = '" . $lesson["sequence"] . "'");
													$teacher = (new User($lesson["teacherId"]))->getFirstname() . " " . (new User($lesson["teacherId"]))->getLastname();
													$students = [];
													if (!isset($studentIds["userId"])) {
														foreach ($studentIds as $student) {
															$students[] = [(new User($student["userId"]))->getFirstname() . " " . (new User($student["userId"]))->getLastname(), $student["present"], $student["userId"]];
														}
													} else {
														$students[] = [(new User($studentIds["userId"]))->getFirstname() . " " . (new User($studentIds["userId"]))->getLastname(), $studentIds["present"], $studentIds["userId"]];
													}
													$allLessons[] = [$lesson["sequence"], $teacher, $students];
													unset($students);
												}
												
												if (isset($add)) {
													unset($add);
												}
											}
											$years[$keyYear][1][] = [$course, $comakership, $allLessons, $result["courseId"]];
											unset($allLesson);
										}
									} else {
										$in = false;
									}
								}
								
								$ir = getIndividualRecords($years);
=======
								
								foreach ($results2 as $result) {
									$year =  date_parse($result["date"])["year"];
									
									if (!in_array($year, $years)) {
										$years[] = $year;
									}
								}
								//var_dump($years);
>>>>>>> origin/master
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
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(75, 176, 1, 0.5)', borderColor: 'rgba(75, 176, 1, 1)', borderWidth: 1 },";
		} elseif ($count == 1) {
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(176, 137, 1, 0.5)', borderColor: 'rgba(176, 137, 1, 1)', borderWidth: 1 },";
		} else {
			$echo .= "{ label: '" . $labels[$count] . "', data: " . json_encode($dataset) . ", backgroundColor: 'rgba(176, 34, 1, 0.5)', borderColor: 'rgba(176, 34, 1, 1)', borderWidth: 1 },";
		}
		$count++;
	}
	return $echo;
}

$echo = generateDatasets($data, $labels);


// filter code
$filter = "?";
if (isset($_GET["type"]) && $_GET["type"] != NULL) {
	$filter .= "type=" . $_GET["type"];
}

if (isset($filter[1])) {
	$filter .= "&";
}

if (isset($_GET["id"]) && $_GET["id"] != NULL) {
	$filter .= "id=" . $_GET["id"] . "&";
}

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
<<<<<<< HEAD
	
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
		padding: 18px 20px 16px 50px;
		color: #ffffff;
		border-bottom: 1px solid #ffffff;
		border-top: 1px solid #ffffff;
		width: calc(100% - 70px);
		font-size: 17px;
		font-style: italic;
		margin-top: -1px;
		transition: all 0.3s;
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
	
	.recordInnerOverview {
		background-color: #ffffff;
		width: calc(100% - 80px);
		padding: 40px;
		display: none;
		min-height: 270px;
	}
	
	.recordInner.expanded {
		display: block;
	}
	
	.recordInnerOverview.expanded {
		display: block;
	}
	
	.recordContainer::before {
		font-size: 30px;
		color: #ffffff;
		content: "\f0da";
		font-family: FontAwesome !important;
		font-style: normal;
		position: absolute;
		margin-left: -28px;
    	margin-top: -7px;
	}
	
	.recordContainer.expand::before {
		content: "\f0d7";
	}
	
	.recordContainer.expand {
		background-color: rgba(255, 255, 255, 0.1);
	}
	
	.recordContainer:hover {
		background-color: rgba(255, 255, 255, 0.1);
	} 
	
	.recordContainer:active {
		background-color: rgba(255, 255, 255, 0.2);
	}
	
	canvas.innerChart {
		/*width: 30% !important;*/
		margin-left: -25%;
		height: auto !important;
		position: absolute;
	}
	
	.individualRecordsContainer {
		width: 50%;
		position: relative;
		z-index: 1;
		margin-left: 45%;    
		max-height: 270px;
    	overflow-y: auto;
	}
	
	.individualRecordsContainer h2  {
		font-size: 28px;
		border-bottom: 2px solid #464646;
		padding: 7px 0px;
	}
	
	.individualRecordsContainer .row {
		padding: 14px 20px 12px;
		margin: 0;
		width: calc(100% - 40px);
		border-bottom: 1px solid #464648;
	}
	
	.row:last-child {
		border-bottom: 0px solid #464648;
	}
	
	.individualRecordsContainer .row .present, .individualRecordsContainer .row .late, .individualRecordsContainer .row .absent {
		width: 92px;
		text-align: center;
		color: #ffffff;
		padding: 8px 0 7px;
		border-radius: 5px;
		float: right;
		margin-top: -8px;
	}
	
	.individualRecordsContainer .row .present { 
		background-color: #4cb002;
	}
	
	.individualRecordsContainer .row .late { 
		background-color: #b08900;
	}
	
	.individualRecordsContainer .row .absent { 
		background-color: #b12202;
	}
	
	.individualRecordsContainer .row a { 
		font-size: 17px;
		outline: none !imporant;
		transition: all 0.3s;
	}
	
	.individualRecordsContainer .row a:hover {
		opacity: 0.8;
	} 
	
	.individualRecordsContainer .row a:active {
		opacity: 1;
		color: #000000;
	}
	
	.individualRecordsContainer .row a:visited { 
		color: #464648;
	}
	
	.fa-external-link {
		font-family: FontAwesome !important;
		font-size: 17px;
		position: absolute;
		margin-left: 11px;
	}
	
	.absoluteRecords {
		width: 200px;
		float: right;
		margin-top: -46px;
		margin-right: -20px;
		font-weight: bold;
	}
	
	.absoluteRecords .present, .absoluteRecords .late, .absoluteRecords .absent {
		width: 40px;
		height: 40px;
		color: #ffffff;
		display: inline-block;
		margin-right: 20px;
		font-size: 17px;
		text-align: center;
		line-height: 43px;
		background-size: contain;
		background-position: center center;
	}
	
	.absoluteRecords .present {
		background-image: url('/view/images/present.png');
	}
	
	.absoluteRecords .late {
		background-image: url('/view/images/late.png');
	}
	
	.absoluteRecords .absent {
		background-image: url('/view/images/absent.png');
	}
	
	.externalLink i {
		color: #ffffff !important;;
	}
	
	#filterContainer {
		padding: 74px 30px 20px;		
		background-color: #464648;
		display: none;
		width: auto;
		color: #ffffff;
		font-size: 14px;
		text-align: left;
		width: 345px;
	}
	
	#filterContainer.show {
		display: block;
	}
	
	#filterContainer.show + #results {
		display: none;
	}
	
	.filterItem {
		color: #ffffff;
		font-size: 17px;
		border: 2px solid #ffffff;
		padding: 7px 20px;
		display: inline-block;
		margin: 0 0 0 -2px;
	}
	
	#filterContainer a:link {
		font-size: 0;
	}
	
	#filterContainer a:hover .filterItem, #filterContainer a:active .filterItem, #filterContainer a .filterItem.active {
		color: #464648;
		background-color: #ffffff;
	}
	
	#all { 
		border-radius: 7px 0 0 7px;
		margin-left: 0;
	}
	
	#year { 
		border-radius: 0 7px 7px 0;
	}
	
	
=======
>>>>>>> origin/master
</style>
<?php if ($userRole != 1) { ?>
<div id="searchBar">
	<div id="filter" onclick="toggleFilter();">
		<i class="fa fa-sliders" aria-hidden="true"></i>
	</div>
	<input id="searchBox" type="text" placeholder="Search..." <?php if ($set || $noData === true) { echo "value='" . $objectName . "'"; } ?> />
	<div id="searchButton" onclick="search();">
		<i class="fa fa-search" aria-hidden="true"></i>
	</div>
	<div id="filterContainer">
		<a href="/overview<?php echo $filter; ?>">
			<div id='all' class="filterItem<?php if (!isset($_GET["filter"])) { echo " active"; } ?>">
				All
			</div>
		</a>
		<a href="/overview<?php echo $filter; ?>filter=days">
			<div id="days" class="filterItem<?php if (isset($_GET["filter"]) && $_GET["filter"] == "days") { echo " active"; } ?>">
				7 Days
			</div>
		</a>
		<a href="/overview<?php echo $filter; ?>filter=month">
			<div id="month" class="filterItem<?php if (isset($_GET["filter"]) && $_GET["filter"] == "month") { echo " active"; } ?>">
				30 Days
			</div>
		</a>
		<a href="/overview<?php echo $filter; ?>filter=year">
			<div id="year" class="filterItem<?php if (isset($_GET["filter"]) && $_GET["filter"] == "year") { echo " active"; } ?>">
				Year
			</div>
		</a>
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
	
	function toggleFilter() {
		document.getElementById("filterContainer").classList.toggle("show");
	}
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
					
					document.getElementById("switch").addEventListener("click", function() {
						mainChart = switchGraph(mainChart);
					}, false);
<<<<<<< HEAD
					<?php } ?>
					
					function collapse(id) {
						var container = "recordContainer" + id;
						var inner = "recordInner" + id;
						document.getElementById(container).classList.toggle("expand");
						document.getElementById(inner).classList.toggle("expanded");
					}
=======
>>>>>>> origin/master
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
				<div class="recordsBox"><div class="noDataRecorded">No data</div></div>
			</div>
		</div>
<?php } ?>
