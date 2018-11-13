<?php
/**
 * File for the class which returns the class map definition
 * @package Niam
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
/**
 * Class which returns the class map definition by the static method NiamClassMap::classMap()
 * @package Niam
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
class NiamClassMap
{
    /**
     * This method returns the array containing the mapping between WSDL structs and generated classes
     * This array is sent to the SoapClient when calling the WS
     * @return array
     */
    final public static function classMap()
    {
        return array (
  'NJT_Ticket' => 'NiamStructNJT_Ticket',
  'NJT_TicketResponse' => 'NiamStructNJT_TicketResponse',
);
    }
}
