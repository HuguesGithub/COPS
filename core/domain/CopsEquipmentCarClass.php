<?php
namespace core\domain;

use core\bean\CopsEquipmentCarBean;
use core\utils\HtmlUtils;

/**
 * Classe CopsEquipmentCarClass
 * @author Hugues
 * @since v1.23.07.19
 * @version v1.23.07.29
 */
class CopsEquipmentCarClass extends CopsEquipmentClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $vehLabel;
    protected $vehCategorie;
    protected $vehSousCategorie;
    protected $vehPlaces;
    protected $vehVitesse;
    protected $vehAcceleration;
    protected $vehPointStructure;
    protected $vehAutonomie;
    protected $vehCarburant;
    protected $vehOptions;
    protected $vehPrix;
    protected $vehAnnee;
    protected $vehReference;
    protected $vehLigneRouge;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsEquipmentCarClass';
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public static function convertElement($row): CopsEquipmentCarClass
    {
        return parent::convertRootElement(new CopsEquipmentCarClass(), $row);
    }

    /**
     * @since v1.23.07.12
     */
    public function getBean(): CopsEquipmentCarBean
    { return new CopsEquipmentCarBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function checkFields(): bool
    {
        $blnOk = true;
        if ($this->vehLabel=='') {
            $blnOk = false;
        }
        return $blnOk;
    }

}
