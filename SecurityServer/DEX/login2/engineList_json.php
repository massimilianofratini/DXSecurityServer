<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

	$conn = ConnectionFactory::getFactory()->getConnection();
	$rs = $conn->query("select * from engine_list");
	$_engines = array();
	while ($row = $rs->fetchArray(SQLITE3_ASSOC)) {
		array_push($_engines, $row);
	}
	mydebug("engine list:".$_engines);
	echo json_encode($_engines);

?>
