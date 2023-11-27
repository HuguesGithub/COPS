<?php
namespace core\daoimpl;

use core\domain\CopsCalPhoneClass;

/**
 * Classe CopsCalPhoneDaoImpl
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalPhoneDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;

    /**
     * Class constructor
     * @since v1.23.12.02
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable   = "wp_7_cops_cal_phone";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_PHONE_ID,
            self::FIELD_CITY_NAME,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_cal_phone
    ////////////////////////////////////

    /**
     * @since v1.23.12.02
     */
    public function getCalPhones(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND cityName LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalPhoneClass(), $request, $attributes);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertCalPhone(CopsCalPhoneClass &$obj): void
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
     * @since v1.23.12.02
     */
    public function updateCalPhone(CopsCalPhoneClass $obj)
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
