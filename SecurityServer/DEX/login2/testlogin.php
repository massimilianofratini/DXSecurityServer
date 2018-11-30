<?php
session_start();
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');

//include_once 'config.php';
//include_once 'mylogger.php';

if ($_POST["password"]=="delphix")
{
	mydebug('PASSWORD CORRECT');
	unset($_SESSION["logoutinprogress"]);
	$cookie_name = "profile";
	$cookie_value = $_POST["profile"];
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/", Cfg::INSTALL_DOMAIN);
	$_COOKIE[$cookie_name]=$cookie_value;
	mydebug('COOKIE SET: '.$cookie_name.'='.$cookie_value);
	//header("refresh:; url=/Server.html");
	//echo "<html><head><script>window.location='/Server.html';</script></head><body>Access Granted</body></html>";
	echo "<html><head><script>window.location='/login2/securityportal.php';</script></head><body>Access Granted</body></html>";
}
else
{
	header("refresh:2; url=/login2/testlogin.html");
	echo "<html><body>Access Denied</body></html>";
}
?>

