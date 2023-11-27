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
 * @version v1.23.12.02
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
     * @version v1.23.12.02
     */
    public function checkFields()
    {
        ////////
        // On vérifie le format de la date. Notamment, s'il n'y a qu'une année, on défini au hasard un jour et un mois.
        if (strlen($this->birthday)==4) {
            $y = $this->birthday;
            $m = round(rand(1, 12));
            $jMax = $m==2 ? 28 : 30;
            $d = round(1, $jMax);
            $this->birthday = strpad($d, 2, '0', STR_PAD_LEFT).'/'.strpad($m, 2, '0', STR_PAD_LEFT).'/'.$y;
        }
        
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
