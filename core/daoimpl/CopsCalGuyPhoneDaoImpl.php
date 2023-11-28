<?php
namespace core\daoimpl;

use core\domain\CopsCalGuyPhoneClass;

/**
 * Classe CopsCalGuyPhoneDaoImpl
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalGuyPhoneDaoImpl extends LocalDaoImpl
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
        $this->dbTable   = "wp_7_cops_cal_guy_phone";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_GUY_ID,
            self::FIELD_PHONENUMBER,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_cal_guy_phone
    ////////////////////////////////////

    /**
     * @since v1.23.12.02
     */
    public function getCalGuyPhones(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND guyId LIKE '%s' AND phoneNumber LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalGuyPhoneClass(), $request, $attributes);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertCalGuyPhone(CopsCalGuyPhoneClass &$obj): void
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
    public function updateCalGuyPhone(CopsCalGuyPhoneClass $obj)
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
    public function deleteCalGuyPhone(CopsCalGuyPhoneClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de suppression
        $request = $this->getDeleteRequest($this->dbTable, $fieldId);
        // On met à jour
        $this->deleteDaoImpl($obj, $fieldId, $request);
    }

}
