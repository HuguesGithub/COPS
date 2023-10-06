<?php
namespace core\domain;

use core\bean\CopsRandomGuyCtrlBean;
use core\domain\CopsCalPhoneClass;
use core\services\CopsRandomGuyServices;

/**
 * Classe CopsRandomGuyCtrlClass
 * @author Hugues
 * @since v1.23.10.07
 */
class CopsRandomGuyCtrlClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $gender;
    protected $nameSet;
    protected $title;
    protected $firstName;
    protected $lastName;
    protected $numberAdress;
    protected $streetAdress;
    protected $city;
    protected $zipCode;
    protected $emailAdress;
    protected $telephoneNumber;
    protected $birthday;
    protected $occupation;
    protected $company;
    protected $vehicle;
    protected $color;
    protected $kilograms;
    protected $centimeters;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.10.07
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsRandomGuyCtrlClass';
    }

    /**
     * @since v1.23.10.07
     */
    public static function convertElement($row): CopsRandomGuyCtrlClass
    { return parent::convertRootElement(new CopsRandomGuyCtrlClass(), $row); }

    /**
     * @since v1.23.10.07
     */
    public function getBean(): CopsRandomGuyCtrlBean
    { return new CopsRandomGuyCtrlBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    public function getFullName(): string
    { return $this->title.' '.$this->firstName.' '.$this->lastName; }

    public function getCalZipCode()
    {
        $objServices = new CopsRandomGuyServices();
        return $this->zipCode=='' ? new CopsCalPhoneClass() : $objServices->getZipCode($this->zipCode);
    }

    public function getCalPhone()
    {
        $objServices = new CopsRandomGuyServices();
        $baseNumber = substr($this->telephoneNumber, 0, 7);
        return $objServices->getPhone($baseNumber);
    }

}
