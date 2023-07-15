<?php
namespace core\domain;

use core\bean\CopsEquipmentWeaponBean;
use core\services\CopsIndexServices;

/**
 * Classe CopsEquipmentWeaponClass
 * @author Hugues
 * @since v1.23.07.09
 * @version v1.23.07.15
 */
class CopsEquipmentWeaponClass extends CopsEquipmentClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $nomArme;
    protected $typeArme;
    protected $compUtilisee;
    protected $scorePr;
    protected $scorePu;
    protected $scoreFa;
    protected $scoreDis;
    protected $scoreVrc;
    protected $portee;
    protected $scoreVc;
    protected $scoreCt;
    protected $munitions;
    protected $prix;
    protected $tomeIdxId;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsEquipmentWeaponClass';
    }

    /**
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public static function convertElement($row): CopsEquipmentWeaponClass
    {
        return parent::convertRootElement(new CopsEquipmentWeaponClass(), $row);
    }

    /**
     * @since v1.23.07.12
     */
    public function getBean(): CopsEquipmentWeaponBean
    { return new CopsEquipmentWeaponBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function checkFields(): bool
    {
        $blnOk = true;
        if ($this->nomArme=='') {
            $blnOk = false;
        }
        return $blnOk;
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function getTome(): CopsIndexTomeClass
    {
        $objCopsIndexServices = new CopsIndexServices();
        return $objCopsIndexServices->getCopsIndexTome($this->tomeIdxId);
    }

}
