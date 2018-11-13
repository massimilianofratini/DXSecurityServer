<?php 
include 'mylogger.php';
include 'conn.php';

	$conn = ConnectionFactory::getFactory()->getConnection();
	$rs = $conn->query("select * from status_type");
	$_status = array();
	while ($row = $rs->fetchArray(fetchArray)) {
		array_push($_status, $row);
	}
	mydebug("status list:".$_status);
	echo json_encode($_status);

?>
