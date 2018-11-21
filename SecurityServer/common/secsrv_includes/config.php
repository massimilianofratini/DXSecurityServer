<?php
//Uncomment the following line to use DEV config file
define('DEV_ENVIRONMENT',true);

if (defined(DEV_ENVIRONMENT))
    include_once(__DIR__ . '/config_dev.php');
else
    include_once(__DIR__ . '/config_prod.php');

?>