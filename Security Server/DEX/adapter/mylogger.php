<?php
$debug_enabled=true;

function mydebug ($message) {
	global $debug_enabled;
	
	if ($debug_enabled) {
		$bt = debug_backtrace();
		$caller = array_shift($bt);
		date_default_timezone_set("Europe/Rome");
		error_log("[".$caller['file'].":".$caller['line']."]\n\t".$message);         
	}
}
?>
