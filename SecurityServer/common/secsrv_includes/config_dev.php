<?php
//Dev environment config
Class Cfg {
    const INSTALL_DIR='C:/Users/delphix_admin/Documents/DXSecServerPrj/phpstorm/SecurityServer';
    const DXSECSERVER_DIR='C:/Users/delphix_admin/Documents/DXSecServerPrj/phpstorm/SecurityServer/DXSecServer';
    const INSTALL_DOMAIN=".delphix.local";
    const PORTAL_URL="http://portal.delphix.local";
    const USERDB="C:/Users/delphix_admin/Documents/DXSecServerPrj/phpstorm/SecurityServer/database/userdb_Working.db";
    const ADMINAPP="ADMIN ALL";
    const ADMINGROUP_DE="ADMIN_DE";
    const ADMINGROUP_MASK="ADMIN ALL";
    const SESSION_DURATION="30"; //in  minutes
    const SECSRVSESSIONID="secsrvsessionid";

    //LDAP AUTH PARAMETERS
    const LDAP_HOST='10.173.83.114';//CHANGE THIS TO THE CORRECT LDAP SERVER
    const LDAP_PORT='389';
    const LDAP_BASEDN='O=Telecom Italia Group';//CHANGE THIS TO THE CORRECT BASE DN
    const LDAP_USERSDN='OU=Personale Esterno,OU=Dipendenti,OU=Telecomitalia';//CHANGE THIS TO THE CORRECT USER OU/CN

    //NIAM INTEGRATION PARAMETERS
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

    //DEBUG PARAMETERS
    const DEBUG_ENABLED=true;
    const DEBUG_FILTER='/.*/i';
    const DEBUG_LOGLEVEL=10;
    const DEBUG_DIR="C:/Users/delphix_admin/Documents/DXSecServerPrj/phpstorm/SecurityServer/DXSecServer/log";
    const DEBUG_FILE_SIZE=5242880;
    const DEBUG_EXTINFO=false;

}

function require_once_common ($inc_file) {
    require_once(Cfg::INSTALL_DIR . '/common/secsrv_includes/' . $inc_file);
}
?>