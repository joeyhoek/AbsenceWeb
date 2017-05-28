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

if (isset($_GET["courseId"])) {
	//insertquerydingetje
		
}

// als docent in tabelstaat, zoja, laadt courseoverview, zoniet -> newCourse

$page = "courseOverview";
$pageTitle = "Lesson overview";
//
$page = "newCourse";
$pageTitle = "Start a new lesson";


?>