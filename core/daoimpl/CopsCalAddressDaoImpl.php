<?php
namespace core\daoimpl;

use core\domain\CopsCalAddressClass;

/**
 * Classe CopsCalAddressDaoImpl
 * @author Hugues
 * @since v1.23.11.25
 * @version v1.23.12.02
 */
class CopsCalAddressDaoImpl extends LocalDaoImpl
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
        $this->dbTable   = "wp_7_cops_cal_address";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_HOUSE_NUMBER,
            self::FIELD_ST_DIRECTION,
            self::FIELD_ST_NAME,
            self::FIELD_ST_SUFFIX,
            self::FIELD_ST_SUF_DIRECTION,
            self::FIELD_ZIPCODE,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_cal_guy
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     * @version v1.23.12.02
     */
    public function getCalAddresses(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND streetDirection LIKE '%s' AND streetName LIKE '%s' ";
        $request .= " AND streetSuffix LIKE '%s' AND streetSuffixDirection LIKE '%s' AND zipCode LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalAddressClass(), $request, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalAddress(CopsCalAddressClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.11.25
     */
    public function updateCalAddress(CopsCalAddressClass $obj)
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
