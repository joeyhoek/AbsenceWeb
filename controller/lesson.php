<?php 
namespace Team10\Absence\Controller;
use Team10\Absence\Model\User as User;
use Team10\Absence\Model\ClassObj as ClassObj;
use Team10\Absence\Model\Course as Course;
use Team10\Absence\Model\Connection as Connection;


if ($userRole == 2 || $userRole == 3 || $userRole == 4) {
	
	// if in database
	$page = "courseOverview";
	$pageTitle = "Lesson overview";
	
	//else
	
	
	if (isset($_GET["id"])) {
		$object = new Course($_GET["id"]);
		$objectName = $object->getName();
	}
	
	
	if (isset($_GET["courseId"])) {
		//insertquerydingetje
		$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);


		$connection->query("INSERT INTO lessons (courseId) VALUES ('" . $_GET["courseId"] . "')");

	}

	// als docent in tabelstaat, zoja, laadt courseoverview, zoniet -> newCourse

	
	//
	$page = "newCourse";
	$pageTitle = "Start a new lesson";

}

?>