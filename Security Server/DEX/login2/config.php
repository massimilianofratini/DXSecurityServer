<?php
Class Cfg {
	const INSTALL_DIR="/var/www/DE";
	const INSTALL_DOMAIN=".delphix.telecomitalia.local";
	const USERDB="/var/www/DE/login2/userdb.db";
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
}

#session_start();
#$_SERVER[Cfg::SM_UID]="aaaaa";
?>