<?php
require_once 'conn.php';
require_once 'config.php';
require_once 'mylogger.php';

###
# Session info:
//session_id
//time_created
//username
//access_profile_name
//username_1
//group_notes	group_name
//group_type
//app_name
//de_externalname
//de_username
//de_password
//access_profile_name_1
//description
//externalurl
//internalname
//internalip

function getSessionInfo($sessid)
{
    mydebug("Looking for session :".$sessid);
    if ( ! isset($sessid) or strlen($sessid)==0 )
        return false;

    $conn = ConnectionFactory::getFactory()->getConnection();
    $session_duration_minutes = '"+'.Cfg::SESSION_DURATION.' minutes"'; //sqlite syntax for additional minutes

    #query for user with valid session id,  and return first row only
	$query="select * from SecServer_sessions s, users_accessprofile a where s.session_id='".$sessid."' 
				and s.username=a.username 
				and s.access_profile_name=a.access_profile_name
				and datetime(time_created,$session_duration_minutes)>datetime('now','UTC')";
    mydebug("query:".print_r($query,true));

    $rs = $conn->query($query);
	$row = $rs->fetchArray(SQLITE3_ASSOC);
	mydebug("session:".print_r($row,true));
	return $row;
}

function closeSecServerSession($sessid)
{
    mydebug("Closing session :".$sessid);
    if ( ! isset($sessid) or strlen($sessid)==0 )
        return false;

    $conn = ConnectionFactory::getFactory()->getConnection();

    #query for user with valid session id,  and return first row only
    $query="update SecServer_sessions set session_id='CLOSED-".$sessid."' where session_id='".$sessid."'";
    mydebug("Close query:".print_r($query,true));

    $rs = $conn->exec($query);
    mydebug("closed ession:".print_r($rs,true));
    return;
}
?>
