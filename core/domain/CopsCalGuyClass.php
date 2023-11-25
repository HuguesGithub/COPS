<?php
namespace core\domain;

use core\bean\CopsCalGuyBean;
use core\domain\CopsCalPhoneClass;
use core\services\CopsCalGuyServices;
use core\services\CopsCalGuyAddressServices;

/**
 * Classe CopsCalGuyClass
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyClass extends LocalDomainClass
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
    protected $birthday;
    protected $kilograms;
    protected $centimeters;
    protected $genkey;
    
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
        $this->stringClass = 'core\domain\CopsCalGuyClass';
        $this->initServices();
    }

    /**
     * @since v1.23.11.25
     */
    public function initServices()
    {
        $this->objCalGuyAddressServices = new CopsCalGuyAddressServices();
    }

    /**
     * @since v1.23.11.25
     */
    public static function convertElement($row): CopsCalGuyClass
    { return parent::convertRootElement(new CopsCalGuyClass(), $row); }

    /**
     * @since v1.23.11.25
     */
    public function getBean(): CopsCalGuyBean
    { return new CopsCalGuyBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getFullName(): string
    { return $this->title.' '.$this->firstName.' '.$this->lastName; }

    /**
     * @since v1.23.11.25
     */
    public function checkFields()
    {
        $blnOk = true;
        if ($this->firstName=='' || $this->lastName=='') {
            $blnOk = false;
        }
        return $blnOk;
    }

    /**
     * @since v1.23.11.25
     */
    public function getCalGuyAddresses(): array
    {
        // On va aller chercher dans cal_guy_address
        // les entrées où guyId vaut $this->id
        // Puis on renvoie un tableau des résultats
        return $this->objCalGuyAddressServices->getCalGuyAddresses([self::FIELD_GUY_ID=>$this->id]);
    }
}
