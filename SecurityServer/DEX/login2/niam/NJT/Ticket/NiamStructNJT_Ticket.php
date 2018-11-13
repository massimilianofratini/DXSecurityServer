<?php
/**
 * File for class NiamStructNJT_Ticket
 * @package Niam
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
/**
 * This class stands for NiamStructNJT_Ticket originally named NJT_Ticket
 * Meta informations extracted from the WSDL
 * - from schema : var/wsdltophp.com/storage/wsdls/1820eaa0153a13dc8d6d1795fb2d9920/wsdl.xml
 * @package Niam
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2016-01-21
 */
class NiamStructNJT_Ticket extends NiamWsdlClass
{
    /**
     * The xmlContent
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var string
     */
    public $xmlContent;
    /**
     * Constructor method for NJT_Ticket
     * @see parent::__construct()
     * @param string $_xmlContent
     * @return NiamStructNJT_Ticket
     */
    public function __construct($_xmlContent)
    {
        parent::__construct(array('xmlContent'=>$_xmlContent),false);
    }
    /**
     * Get xmlContent value
     * @return string
     */
    public function getXmlContent()
    {
        return $this->xmlContent;
    }
    /**
     * Set xmlContent value
     * @param string $_xmlContent the xmlContent
     * @return string
     */
    public function setXmlContent($_xmlContent)
    {
        return ($this->xmlContent = $_xmlContent);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see NiamWsdlClass::__set_state()
     * @uses NiamWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return NiamStructNJT_Ticket
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
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
