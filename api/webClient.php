<?php

namespace Team10\Absence\Api;
use Team10\Absence\Model\Search as Search;

if (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "users"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(true, true, false, false, false, false));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "classrooms"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(false, false, false, false, false, true));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL && isset($_POST["type"]) && $_POST["type"] == "locations"):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(false, false, false, false, true, false));
elseif (isset($_POST["search"]) && $_POST["search"] !== NULL):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(true, true, true, true, false, false));
else:
	header("Location: /404");
endif;

?>