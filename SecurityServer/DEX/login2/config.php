<?php
Class Cfg {
    const INSTALL_DIR="/Users/delphix_admin/Documents/PhpstormProjects/DXSecurityServer/DEX";
    const INSTALL_DOMAIN=".delphix-collaudo.telecomitalia.local";
    const PORTAL_URL="http://portal-dev.delphix-collaudo.telecomitalia.local";
    const USERDB="/Users/delphix_admin/Documents/PHPRunnerProjects/SecServerv98/output/database/userdb_Working.db";
    const ADMINAPP="ADMIN ALL";
    const ADMINGROUP_DE="ADMIN_DE";
    const ADMINGROUP_MASK="ADMIN ALL";
    const SESSION_DURATION="30"; //in  minutes
    const SECSRVSESSIONID="secsrvsessionid";
    const SM_UID="HTTP_UID";
    const SM_LOGOFFURL="/siteminderagent/forms/logoff.fcc";
    const NIAM_WSDL="asynch.wsdl";
    const NIAM_SSLVERSION=3; // 0-->HTTP; 3-->HTTPS
    const NIAM_WSURL="https://10.0.65.146:9102";
    const NIAM_WSPORT="9102";
    const NIAM_WSSERVER="10.0.65.146";
    #const NIAM_WSURL="https://127.0.0.1:9102";
    #const NIAM_WSPORT="9102";
    #const NIAM_WSSERVER="127.0.0.1";
    const NIAM_ADAPTERURL="http://127.0.0.1:80/login2/NiamAdapter.php";
    const DEBUG_ENABLED=true;
    const DEBUG_FILTER='/.*/i';
    const DEBUG_LOGLEVEL=10;
    const DEBUG_DIR="/Users/delphix_admin/Documents/PHPRunnerProjects/SecServerv98/output/log";
    const DEBUG_FILE_SIZE=5242880;
    const DEBUG_EXTINFO=false;

}

#session_start();
#$_SERVER[Cfg::SM_UID]="aaaaa";
?>