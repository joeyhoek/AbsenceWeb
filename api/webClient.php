<?php

namespace Team10\Absence\Api;
use Team10\Absence\Model\Search as Search;

if (isset($_POST["search"]) && $_POST["search"] !== NULL):
	$search = new Search($_POST["search"]);
	echo json_encode($search->getResults(true, true, true, true, false));
else:
	header("Location: /404");
endif;

?>