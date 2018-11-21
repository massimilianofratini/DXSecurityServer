<?php
require_once(__DIR__ . '/config.php');


function mydebug ($message, $event="LOG", $loglevel=5) {

    if (Cfg::DEBUG_ENABLED) {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        if (Cfg::DEBUG_EXTINFO) {
            $e = new Exception();
            $message=$e->getTraceAsString().$message;
        }
        else {
            $message=basename($caller['file'])."(".$caller['line'].") : ".$message;
        }
        date_default_timezone_set("Europe/Rome");
        if (preg_match(Cfg::DEBUG_FILTER, $message))
//			error_log($caller['file'].":".$caller['line']."\n\t".$message);
            logger($message, $event, $loglevel);
    }
}

/**
 * Log function with log file rotation
 * and loglevel restrictions
 *
 * @param <int> $level
 * @param <string> $event
 * @param <string> $text
 */
function logger($text, $event = "LOG", $level = 5){
    $maxsize = Cfg::DEBUG_FILE_SIZE; //Max filesize in bytes (e.q. 5MB)
    $dir = Cfg::DEBUG_DIR;
    $filename = "DXSecServer.log";
    $logfile = $dir."/".$filename;
    $loglevel = Cfg::DEBUG_LOGLEVEL;
    if(file_exists($logfile) && (filesize($logfile) > $maxsize)){
        $nb = 1;
        $logfilelist = scandir($dir);
        foreach ($logfilelist as $file) {
            $tmpnb = substr($file, strlen($filename));
            if($nb < $tmpnb){
                $nb = $tmpnb;
            }
        }
        rename($dir.$filename, $dir.$filename.($nb + 1));
    }
    if($level <= $loglevel) {
        $data = date('Y-m-d H:i:s.u')." L".$level." ";
        $data .= $event." - ".$text.PHP_EOL;
        file_put_contents($logfile, $data, FILE_APPEND);
    }
}


?>