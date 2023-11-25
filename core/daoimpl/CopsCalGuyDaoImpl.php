<?php
namespace core\daoimpl;

use core\domain\CopsCalGuyClass;
use core\domain\MySQLClass;
use core\utils\LogUtils;

/**
 * Classe CopsCalGuyDaoImpl
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyDaoImpl extends LocalDaoImpl
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
        $this->dbTable   = "wp_7_cops_cal_guy";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_GENDER,
            self::FIELD_NAMESET,
            self::FIELD_TITLE,
            self::FIELD_FIRSTNAME,
            self::FIELD_LASTNAME,
            self::FIELD_BIRTHDAY,
            self::FIELD_KILOGRAMS,
            self::FIELD_CENTIMETERS,
            self::FIELD_GENKEY,
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
     */
    public function getCalGuys(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND nameSet LIKE '%s' AND title LIKE '%s'";
        $request .= " AND firstName LIKE '%s' AND lastName LIKE '%s' AND genkey LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalGuyClass(), $request, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function getDistinctCalGuyField(array $attributes): array
    {
        $request = "SELECT DISTINCT %s FROM ".$this->dbTable." ORDER BY %s ASC;";
        return $this->selectListDaoImpl(new CopsCalGuyClass(), $request, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalGuy(CopsCalGuyClass &$obj): void
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
    public function updateCalGuy(CopsCalGuyClass $obj)
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
