<?php

namespace Team10\Absence\Api;
use Team10\Absence\Model\Search as Search;

if ($userRole == 4) {
	$students = true;
	$teachers = true;
	$courses = true;
	$classes = true;
	$locations = true;
	$classrooms = true;
} elseif ($userRole == 3) {
	$students = true;
	$teachers = false;
	$courses = true;
	$classes = true;
	$locations = false;
	$classrooms = false;
} elseif ($userRole == 2) {
	$students = true;
	$teachers = false;
	$courses = true;
	$classes = true;
	$locations = false;
	$classrooms = false;
} else {
	$students = false;
	$teachers = false;
	$courses = false;
	$classes = false;
	$locations = false;
	$classrooms = false;
}

if (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "users"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults($students, $teachers, false, false, false, false));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "classrooms"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(false, false, false, false, false, $classrooms));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "locations"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(false, false, false, false, $locations, false));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults($students, $teachers, $courses, $classes, false, false));
else:
	header("Location: /404");
endif;

?>