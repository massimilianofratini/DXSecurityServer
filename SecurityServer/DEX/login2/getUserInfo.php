<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

function get_user_info($usr) {
	
	$username=SQLite3::escapeString($usr);
	$sql = "select * from users,roles_type where username='$username' and status='ACTIVE' and users.role=roles_type.role";
	
	$conn = ConnectionFactory::getFactory()->getConnection();
	
	mydebug("query:".$sql);

	$rs = $conn->query($sql);
	$row = $rs->fetchArray(SQLITE3_ASSOC);
	
	mydebug("Row:".print_r($row,true));
	return $row;
	
}
?>
