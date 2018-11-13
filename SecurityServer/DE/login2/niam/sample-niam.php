<?php
/**
 * Test with Niam for 'var/wsdltophp.com/storage/wsdls/1820eaa0153a13dc8d6d1795fb2d9920/wsdl.xml'
 * @package Niam
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/NiamAutoload.php';
/**
 * Wsdl instanciation infos. By default, nothing has to be set.
 * If you wish to override the SoapClient's options, please refer to the sample below.
 * 
 * This is an associative array as:
 * - the key must be a NiamWsdlClass constant beginning with WSDL_
 * - the value must be the corresponding key value
 * Each option matches the {@link http://www.php.net/manual/en/soapclient.soapclient.php} options
 * 
 * Here is below an example of how you can set the array:
 * $wsdl = array();
 * $wsdl[NiamWsdlClass::WSDL_URL] = 'var/wsdltophp.com/storage/wsdls/1820eaa0153a13dc8d6d1795fb2d9920/wsdl.xml';
 * $wsdl[NiamWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
 * $wsdl[NiamWsdlClass::WSDL_TRACE] = true;
 * $wsdl[NiamWsdlClass::WSDL_LOGIN] = 'myLogin';
 * $wsdl[NiamWsdlClass::WSDL_PASSWD] = '**********';
 * etc....
 * Then instantiate the Service class as: 
 * - $wsdlObject = new NiamWsdlClass($wsdl);
 */
/**
 * Examples
 */
$wsdl = array();
$wsdl[NiamWsdlClass::WSDL_URL] = 'http://localhost:8088/mockWorkerResponseASyncSOAP?WSDL';

$ticketXml='<![CDATA[<Employee><AttributeAD name="RequestID" value="<requestid>" /><AttributeAD name="Result" value="<result>" /><AttributeAD name="Tipo" value="<tipo>" /><AttributeAD name="Sistema" value="<sistema>" /><AttributeAD name="ResultDetail" value="<resultdetail>" /></Employee>]]>';
$ticketXml=str_replace("<requestid>", "123456", $ticketXml);
$ticketXml=str_replace("<result>", "OK", $ticketXml);
$ticketXml=str_replace("<tipo>", "U", $ticketXml);
$ticketXml=str_replace("<sistema>", "DELPHIX", $ticketXml);
$ticketXml=str_replace("<resultdetail>", "OK", $ticketXml);

/*****************************
 * Example for NiamServiceSend
 */
$niamServiceSend = new NiamServiceSend($wsdl);
// sample call for NiamServiceSend::sendResponse()
if($niamServiceSend->sendResponse(new NiamStructNJT_Ticket($ticketXml)))
    print_r($niamServiceSend->getResult());
else
    print_r($niamServiceSend->getLastError());
