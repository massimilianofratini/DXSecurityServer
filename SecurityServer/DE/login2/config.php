<?php
Class Cfg {
    const INSTALL_DIR="/Users/delphix_admin/Documents/PhpstormProjects/DXSecurityServer/DEX";
    const INSTALL_DOMAIN=".delphix-collaudo.telecomitalia.local";
    const USERDB="/Users/delphix_admin/Documents/PHPRunnerProjects/database/userdb_Working.db";
	const SM_UID="HTTP_UID";
	const SM_LOGOFFURL="/siteminderagent/forms/logoff.fcc";
	const NIAM_WSDL="asynch.wsdl";
	const NIAM_SSLVERSION=0; // 0-->HTTP; 3-->HTTPS
	const NIAM_WSURL="http://127.0.0.1:8088/mockWorkerResponseASyncSOAP?WSDL";
	const NIAM_WSPORT="8088";
	const NIAM_WSSERVER="127.0.0.1";
	#const NIAM_WSURL="https://10.0.65.146:9102";
	#const NIAM_WSPORT="9102";
	#const NIAM_WSSERVER="10.0.65.146";
	const NIAM_ADAPTERURL="http://127.0.0.1:8080/login2/NiamAdapter.php";
}
?>