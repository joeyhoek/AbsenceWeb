<?php

namespace Team10\Absence\Api;
use Team10\Absence\Model\Connection as Connection;

require_once("../model/connection.php");

if (isset($_POST["userId"]) && isset($_POST["token"]) && isset($_POST["classId"])):
	require_once("../model/config.php");
	$connection = new Connection(DBHOST, DBUSER, DBPASS, DBNAME);
	$result = $connection->query("SELECT * FROM sessions WHERE userId = '" . $_POST["userId"] . "' and client = 'desktop'");
	if ($result && $result["token"] == $_POST["token"]):
		$records = $connection->query("SELECT * FROM presence WHERE lessonId = " . $_POST["classId"]);
		$classes = $connection->query("SELECT DISTINCT classId FROM lessons WHERE id = " . $_POST["classId"]);
		$total = 0;
		foreach ($classes as $class):
			$total += $connection->query("SELECT COUNT(*) as number FROM users WHERE classId = " . $class["classId"])["number"];
		endforeach;
		$present = 0;
		$toLate = 0;
		$guests = $connection->query("SELECT COUNT(*) as number FROM users, lessons, presence WHERE lessons.id = presence.lessonId AND presence.lessonId = " . $_POST["classId"] . " AND presence.userId = users.id AND users.classId != " . $_POST["classId"])["number"];

		// Check if one presence row in database or multiple
		if (count($records) == 6):
			if ($records["present"] == 1):
				if ($records["toLate"] == 0 || $records["toLate"] == NULL):
					$present = 1;
				else:
					$toLate = 1;
				endif;
			endif;
		else:
			foreach($records as $record):
				if ($record["present"] == 1):
					if ($record["toLate"] == 0 || $record["toLate"] == NULL):
						$present += 1;
					else:
						$toLate += 1;
					endif;
				endif;
			endforeach;
		endif;
		
		$absent = $total - ($present + $toLate) + $guests;

		echo json_encode([
			"present" => $present,
			"toLate" => $toLate,
			"absent" => $absent,
			"finished" => "Done"
		]);
	else: 
		header("Location: /404");
	endif;
else:
	header("Location: /404");
endif;

?>