<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	$result = array();

	$conn = ConnectionFactory::getFactory()->getConnection();
	
	$rs = $conn->query("select count(*) from users");
	$row = $rs->fetchArray(fetchArray);
	$result["total"] = $row[0];
	$rs = $conn->query("select * from users limit $offset,$rows");
	
	$items = array();
	while ($row = $rs->fetchArray(fetchArray)) {
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>
