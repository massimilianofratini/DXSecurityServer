<?php
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);

require_once('../../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');
require_once __DIR__ . '/NiamAutoload.php';


$wsdl = array();
$wsdl[NiamWsdlClass::WSDL_URL] = Cfg::NIAM_WSDL;
$wsdl[NiamWsdlClass::WSDL_WSURL] = Cfg::NIAM_WSURL;
$wsdl[NiamWsdlClass::WSDL_SSLVERSION] = Cfg::NIAM_SSLVERSION; // 0 --> HTTP; 3-->HTTPS
$wsdl[NiamWsdlClass::WSDL_TRACE] = true;

$conn = ConnectionFactory::getFactory()->getConnection();
$sql = "select * from niam where ack='NOACK'";
mydebug($sql);
$result = $conn->query($sql);
while ($r=$result->fetchArray(SQLITE3_ASSOC)) {
	mydebug("operazione: ".print_r($r,true));
	
	$numoperazione=$r['numoperazione'];
	$opstatus=$r['opstatus'];
	$opdetails=$r['opdetails'];
	$codsistema=$r['codsistema'];

	$ticketXml='<![CDATA[<Employee><AttributeAD name="RequestID" value="<requestid>" /><AttributeAD name="Result" value="<result>" /><AttributeAD name="Tipo" value="<tipo>" /><AttributeAD name="Sistema" value="<sistema>" /><AttributeAD name="ResultDetail" value="<resultdetail>" /></Employee>]]>';
	$ticketXml=str_replace("<requestid>", $numoperazione, $ticketXml);
	$ticketXml=str_replace("<result>", $opstatus, $ticketXml);
	$ticketXml=str_replace("<tipo>", "U", $ticketXml);
	$ticketXml=str_replace("<sistema>", $codsistema, $ticketXml);
	$ticketXml=str_replace("<resultdetail>", $opdetails, $ticketXml);

	mydebug("Calling WS with:".$ticketXml);
	$niamServiceSend = new NiamServiceSend($wsdl);
	$niamWSresult = $niamServiceSend->sendResponse(new NiamStructNJT_Ticket($ticketXml));
	mydebug("WS result: ".print_r($niamWSresult->NJT_TicketResult,true));
    if ($niamWSresult->NJT_TicketResult=="OK") {
		$sql = "update niam set ack='ACK' where numoperazione='$numoperazione'";
		mydebug($sql);
    	$conn->exec($sql);
	}
	else
		mydebug("WS error: ".print_r($niamServiceSend->getLastError(),true));
}