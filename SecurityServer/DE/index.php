<?php
session_start();
include_once './login2/config.php';
include_once './login2/mylogger.php';
include_once './login2/sm_session.php';

mydebug('STARTING SESSION');
unset($_SESSION["logoutinprogress"]);

$prf = store_profile_in_session();
if ($prf==false)
{
	mydebug("Session profile not found");
	echo "<html><head></head><body>Delphix did not receive profile information, cannot go ahead</body></html>";
}
else 
{
	mydebug("Session profile is ".$_SESSION["profile"]);
	echo "<html><head><script>window.location='/login2/securityportal.php';</script></head><body>Access Granted</body></html>";
}
?>

