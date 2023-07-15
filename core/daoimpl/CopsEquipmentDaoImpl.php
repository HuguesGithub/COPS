<?php
namespace core\daoimpl;

use core\domain\CopsEquipmentWeaponClass;

/**
 * Classe CopsEquipmentDaoImpl
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.07.15
 */
class CopsEquipmentDaoImpl extends LocalDaoImpl
{
    /**
     * Class constructor
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable_wpn  = "wp_7_cops_weapon";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields_wpn = [
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
            ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_weapon
    ////////////////////////////////////

    /**
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public function getWeapons(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_wpn), $this->dbTable_wpn);
        $request .= " WHERE id LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEquipmentWeaponClass(), $request, $attributes);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function insertWeapon(CopsEquipmentWeaponClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields_wpn;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTable_wpn);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function updateWeapon(CopsEquipmentWeaponClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFields_wpn;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable_wpn, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

}
