<?php
namespace core\domain;

use core\bean\CopsCalAddressBean;
use core\services\CopsCalAddressServices;
use core\services\CopsCalZipcodeServices;

/**
 * Classe CopsCalAddressClass
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalAddressClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $houseNumber;
    protected $streetDirection;
    protected $streetName;
    protected $streetSuffix;
    protected $streetSuffixDirection;
    protected $zipCode;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.11.25
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsCalAddressClass';
        $this->initServices();
    }

    /**
     * @since v1.23.11.25
     */
    public function initServices(): void
    {
        $this->objZipcodeServices = new CopsCalZipcodeServices();
    }

    /**
     * @since v1.23.11.25
     */
    public static function convertElement($row): CopsCalAddressClass
    { return parent::convertRootElement(new CopsCalAddressClass(), $row); }

    /**
     * @since v1.23.11.25
     */
    public function getBean(): CopsCalAddressBean
    { return new CopsCalAddressBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getFullAddress(): string
    {
        $str = '';
        switch ($this->streetDirection) {
            case 'N' :
                $str .= 'North ';
            break;
            case 'W' :
                $str .= 'West ';
            break;
            case 'E' :
                $str .= 'East ';
            break;
            case 'S' :
                $str .= 'South ';
            break;
            default :
                $str .= '';
            break;
        }
        $str .= ucwords(strtolower($this->streetName)).' ';
        switch ($this->streetSuffix) {
            case 'ST' :
                $str .= 'Street';
            break;
            case 'AVE' :
                $str .= 'Avenue';
            break;
            default :
                $str .= '';
            break;
        }
        $str .= ', '.$this->zipCode;
        $objZipcode = $this->getZipcode();
        $str .= ', '.$objZipcode->getField(self::FIELD_PRIMARY_CITY);
        return $str;
    }

    /**
     * @since v1.23.11.25
     */
    public function getZipcode(): CopsCalZipcodeClass
    { return $this->objZipcodeServices->getCalZipcode($this->zipCode); }

}
