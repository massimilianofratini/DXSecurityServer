<?php 
require_once 'conn.php';
require_once 'mylogger.php';


function getSessionInfo($sessid)
{
	$conn = ConnectionFactory::getFactory()->getConnection();
	
	$rs = $conn->query("select * from SecServer_session where session_id='".$sessid."'");
	$sessions = array();
	while ($row = $rs->fetchArray(SQLITE3_ASSOC)) {
		array_push($sessions, $row);
	}
	mydebug("session list:".print_r($sessions,true));
	return $sessions;
}
?>
