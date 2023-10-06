<?php
namespace core\domain;

use core\bean\CopsRandomGuyBean;
use core\domain\CopsCalPhoneClass;
use core\services\CopsRandomGuyServices;

/**
 * Classe CopsRandomGuyClass
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsRandomGuyClass extends LocalDomainClass
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
     * @since v1.23.09.16
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsRandomGuyClass';
    }

    /**
     * @since v1.23.09.16
     */
    public static function convertElement($row): CopsRandomGuyClass
    { return parent::convertRootElement(new CopsRandomGuyClass(), $row); }

    /**
     * @since v1.23.09.16
     */
    public function getBean(): CopsRandomGuyBean
    { return new CopsRandomGuyBean($this); }

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
