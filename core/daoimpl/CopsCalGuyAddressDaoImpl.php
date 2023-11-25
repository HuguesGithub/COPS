<?php
namespace core\daoimpl;

use core\domain\CopsCalGuyAddressClass;

/**
 * Classe CopsCalGuyAddressDaoImpl
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyAddressDaoImpl extends LocalDaoImpl
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
        $this->dbTable   = "wp_7_cops_cal_guy_address";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_GUY_ID,
            self::FIELD_ADDRESS_ID,
            self::FIELD_NUMBER,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_cal_guy_address
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getCalGuyAddresses(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND guyId LIKE '%s' AND addressId LIKE '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalGuyAddressClass(), $request, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalGuyAddress(CopsCalGuyAddressClass &$obj): void
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
    public function updateCalGuyAddress(CopsCalGuyAddressClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

}
