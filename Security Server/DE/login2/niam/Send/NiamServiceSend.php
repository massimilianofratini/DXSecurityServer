<?php
/**
 * File for class NiamServiceSend
 * @package Niam
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
/**
 * This class stands for NiamServiceSend originally named Send
 * @package Niam
 * @subpackage Services
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
class NiamServiceSend extends NiamWsdlClass
{
    /**
     * Method to call the operation originally named sendResponse
     * @uses NiamWsdlClass::getSoapClient()
     * @uses NiamWsdlClass::setResult()
     * @uses NiamWsdlClass::saveLastError()
     * @param NiamStructNJT_Ticket $_niamStructNJT_Ticket
     * @return NiamStructNJT_TicketResponse
     */
    public function sendResponse(NiamStructNJT_Ticket $_niamStructNJT_Ticket)
    {
        try
        {
            return $this->setResult(self::getSoapClient()->sendResponse($_niamStructNJT_Ticket));
        }
        catch(SoapFault $soapFault)
        {
            return !$this->saveLastError(__METHOD__,$soapFault);
        }
    }
    /**
     * Returns the result
     * @see NiamWsdlClass::getResult()
     * @return NiamStructNJT_TicketResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}
