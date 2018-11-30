<?php
//Uncomment the following line to use DEV config file
//define('DEV_ENVIRONMENT','true');

if (defined('DEV_ENVIRONMENT')) {
    //echo "----------- DEV -------------";
    include_once(__DIR__ . '/config_dev.php');
}
else {
    //echo "----------- PROD -------------";
    include_once(__DIR__ . '/config_prod.php');
}

function require_once_common ($inc_file) {
    require_once(Cfg::INSTALL_DIR . '/common/secsrv_includes/' . $inc_file);
}
?>