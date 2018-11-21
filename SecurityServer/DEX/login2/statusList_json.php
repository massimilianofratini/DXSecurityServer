<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

	$conn = ConnectionFactory::getFactory()->getConnection();
	$rs = $conn->query("select * from status_type");
	$_status = array();
	while ($row = $rs->fetchArray(fetchArray)) {
		array_push($_status, $row);
	}
	mydebug("status list:".$_status);
	echo json_encode($_status);

?>
