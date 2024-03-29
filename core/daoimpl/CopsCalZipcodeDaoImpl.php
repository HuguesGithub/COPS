<?php
namespace core\daoimpl;

use core\domain\CopsCalZipcodeClass;

/**
 * Classe CopsCalZipcodeDaoImpl
 * @author Hugues
 * @since v1.23.11.25
 * @version v1.23.12.02
 */
class CopsCalZipcodeDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;

    /**
     * Class constructor
     * @since v1.23.11.25
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable   = "wp_7_cops_cal_zipcode";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ZIP,
            self::FIELD_TYPE,
            self::FIELD_DECOMMISSIONED,
            self::FIELD_PRIMARY_CITY,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_cal_zipcode
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     * @version v1.23.12.02
     */
    public function getCalZipcodes(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE zip LIKE '%s' AND primaryCity LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalZipcodeClass(), $request, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalZipcode(CopsCalZipcodeClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ZIP);
    }

    /**
     * @since v1.23.11.25
     */
    public function updateCalZipcode(CopsCalZipcodeClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

    /**
     * @since v1.23.12.02
     */
    public function getDistinctFieldValues($objDefault, array $attributes): array
    {
        $field = array_shift($attributes);
        $attributes = [$field, $this->dbTable, $field];
        return parent::getDistinctFieldValues($objDefault, $attributes);
    }

}
