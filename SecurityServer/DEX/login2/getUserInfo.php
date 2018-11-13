<?php
require_once 'mylogger.php';
require_once 'conn.php';

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
