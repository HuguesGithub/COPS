<?php
namespace core\daoimpl;

use core\domain\CopsEquipmentCarClass;
use core\domain\CopsEquipmentWeaponClass;

/**
 * Classe CopsEquipmentDaoImpl
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.07.22
 */
class CopsEquipmentDaoImpl extends LocalDaoImpl
{
    public $dbTableWpn;
    public $dbTableCar;
    public $dbFieldsWpn;
    public $dbFieldsCar;

    /**
     * Class constructor
     * @since v1.23.07.12
     * @version v1.23.07.22
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTableWpn  = "wp_7_cops_weapon";
        $this->dbTableCar  = "wp_7_cops_vehicule";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFieldsWpn = [
            self::FIELD_ID,
            self::FIELD_NOM_ARME,
            self::FIELD_TYPE_ARME,
            self::FIELD_SKILL_USE,
            self::FIELD_SCORE_PR,
            self::FIELD_SCORE_PU,
            self::FIELD_SCORE_FA,
            self::FIELD_SCORE_DIS,
            self::FIELD_SCORE_VRC,
            self::FIELD_PORTEE,
            self::FIELD_SCORE_VC,
            self::FIELD_SCORE_CT,
            self::FIELD_MUNITIONS,
            self::FIELD_PRIX,
            self::FIELD_TOME_IDX_ID,
        ];
        $this->dbFieldsCar = [
            self::FIELD_ID,
            self::FIELD_VEH_LABEL,
            self::FIELD_VEH_CATEG,
            self::FIELD_VEH_SS_CATEG,
            self::FIELD_VEH_PLACES,
            self::FIELD_VEH_SPEED,
            self::FIELD_VEH_ACCELERE,
            self::FIELD_VEH_PS,
            self::FIELD_VEH_AUTONOMIE,
            self::FIELD_VEH_FUEL,
            self::FIELD_VEH_OPTIONS,
            self::FIELD_VEH_PRICE,
            self::FIELD_VEH_YEAR,
            self::FIELD_VEH_REFERENCE,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_vehicule
    ////////////////////////////////////

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getVehicles(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCar), $this->dbTableCar);
        $request .= " WHERE id LIKE '%s' AND vehCategorie LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEquipmentCarClass(), $request, $attributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function insertVehicle(CopsEquipmentCarClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsCar;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTableCar);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function updateVehicle(CopsEquipmentCarClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsCar;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTableCar, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

    ////////////////////////////////////
    // wp_7_cops_weapon
    ////////////////////////////////////

    /**
     * @since v1.23.07.12
     * @version v1.23.07.22
     */
    public function getWeapons(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsWpn), $this->dbTableWpn);
        $request .= " WHERE id LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEquipmentWeaponClass(), $request, $attributes);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.22
     */
    public function insertWeapon(CopsEquipmentWeaponClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsWpn;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTableWpn);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.22
     */
    public function updateWeapon(CopsEquipmentWeaponClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsWpn;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTableWpn, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

}
