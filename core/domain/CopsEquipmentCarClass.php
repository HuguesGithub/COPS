<?php
namespace core\domain;

use core\bean\CopsEquipmentCarBean;
use core\utils\HtmlUtils;

/**
 * Classe CopsEquipmentCarClass
 * @author Hugues
 * @since v1.23.07.19
 * @version v1.23.07.22
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

    /**
     * @since v1.23.07.21
     * @version v1.23.07.22
     */
    public function getEditInterfaceAttributes(): array
    {
        //////////////////////////////////////////////////////////
        // Récupération du type de véhicule
        // On va construire une liste déroulante.
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_VEH_CATEG,
            self::FIELD_ID   => self::FIELD_VEH_CATEG,
        ];
        $arrTypeVehicle = [
            'aquatique' => 'Amphibie',
            'moto'      => 'Moto',
            'utilitaire'=> 'Utilitaire',
            'voiture'   => 'Voiture',
        ];
        $selTypeCategValue = $this->getField(self::FIELD_VEH_CATEG);
        // TODO : $selTypeSsCategValue = $this->getField(self::FIELD_VEH_SS_CATEG);
        $strContentSel = '';
        foreach($arrTypeVehicle as $value => $label) {
            $strContentSel .= HtmlUtils::getOption($label, $value, $value==$selTypeCategValue);
        }
        $selTypeCateg = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        //////////////////////////////////////////////////////////

        return [
            // Id
            $this->getField(self::FIELD_ID),
            // Nom
            $this->getField(self::FIELD_VEH_LABEL),
            // Référence
            $this->getField(self::FIELD_VEH_REFERENCE),
            // Type de véhicule (liste)
            $selTypeCateg,
            // Sous Catégorie de véhicule (liste)
            '',// TODO $selTypeSsCategValue,
            // Occupants
            $this->getField(self::FIELD_VEH_PLACES),
            // Vitesse
            $this->getField(self::FIELD_VEH_SPEED),
            // Accélération
            $this->getField(self::FIELD_VEH_ACCELERE),
            // Autonomie
            $this->getField(self::FIELD_VEH_AUTONOMIE),
            // Carburant
            $this->getField(self::FIELD_VEH_FUEL),
            // Prix
            $this->getField(self::FIELD_VEH_PRICE),
            // Points de Structure
            $this->getField(self::FIELD_VEH_PS),
            // Année
            $this->getField(self::FIELD_VEH_YEAR),
            // Options
            $this->getField(self::FIELD_VEH_OPTIONS),
        ];

    }

}
