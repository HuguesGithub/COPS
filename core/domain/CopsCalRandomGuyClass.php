<?php
namespace core\domain;

use core\bean\CopsCalRandomGuyBean;
use core\domain\CopsCalPhoneClass;
use core\services\CopsRandomGuyServices;

/**
 * Classe CopsCalRandomGuyClass
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsCalRandomGuyClass extends LocalDomainClass
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
        $this->stringClass = 'core\domain\CopsCalRandomGuyClass';
    }

    /**
     * @since v1.23.09.16
     */
    public static function convertElement($row): CopsCalRandomGuyClass
    { return parent::convertRootElement(new CopsCalRandomGuyClass(), $row); }

    /**
     * @since v1.23.09.16
     */
    public function getBean(): CopsCalRandomGuyBean
    { return new CopsCalRandomGuyBean($this); }

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
