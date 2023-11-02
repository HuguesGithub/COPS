<?php
namespace core\daoimpl;

use core\domain\CopsCalRandomGuyClass;
use core\domain\CopsCalPhoneClass;
use core\domain\CopsCalZipCodeClass;
use core\domain\MySQLClass;
use core\utils\LogUtils;

/**
 * Classe CopsRandomGuyDaoImpl
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsRandomGuyDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbTableZc;
    private $dbTablePh;
    private $dbFields;
    private $dbFieldsZc;
    private $dbFieldsPh;

    /**
     * Class constructor
     * @since v1.23.09.16
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable   = "wp_7_cops_cal_random_guy";
        $this->dbTableZc = "wp_7_cops_cal_zipcode";
        $this->dbTablePh = "wp_7_cops_cal_phone";
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
            self::FIELD_NBADRESS,
            self::FIELD_STADRESS,
            self::FIELD_CITY,
            self::FIELD_ZIPCODE,
            self::FIELD_EMAILADRESS,
            self::FIELD_PHONENUMBER,
            self::FIELD_BIRTHDAY,
            self::FIELD_OCCUPATION,
            self::FIELD_COMPANY,
            self::FIELD_VEHICLE,
            self::FIELD_COLOR,
            self::FIELD_KILOGRAMS,
            self::FIELD_CENTIMETERS,
        ];
        $this->dbFieldsZc = [
            self::FIELD_ZIP,
            'type',
            'decommissioned',
            self::FIELD_PRIMARY_CITY,
        ];
        $this->dbFieldsPh = [
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

    public function getTripletAdresse(array $attributes): array
    {
        $request  = "SELECT phoneId, zip, cityName FROM ".$this->dbTablePh;
        $request .= " INNER JOIN ".$this->dbTableZc." ON cityName=primaryCity";
        $request .= " WHERE phoneId LIKE '%s' AND zip LIKE '%s' AND cityName LIKE '%s'";
        $request .= " ORDER BY phoneId ASC, zip ASC, cityName ASC LIMIT 101;";

        //////////////////////////////
        // Préparation de la requête
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        if ($this->arrLogs['select']) {
            LogUtils::logRequest($prepRequest);
        }
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = $row;
            }
        }
        return $objItems;
    }

    ////////////////////////////////////
    // wp_7_cops_cal_random_guy
    ////////////////////////////////////

    /**
     * @since v1.23.09.16
     */
    public function getGuys(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND nameSet LIKE '%s' AND city LIKE '%s' AND zipCode LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalRandomGuyClass(), $request, $attributes);
    }

    /**
     * @since v1.23.10.14
     */
    public function insertCalGuy(CopsCalRandomGuyClass &$obj): void
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
     * @since v1.23.10.14
     */
    public function updateCalGuy(CopsCalRandomGuyClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

    public function getDistinctGuyField(array $attributes): array
    {
        $request = "SELECT DISTINCT %s FROM ".$this->dbTable." ORDER BY %s ASC;";
        return $this->selectListDaoImpl(new CopsCalRandomGuyClass(), $request, $attributes);
    }

    ////////////////////////////////////
    // wp_7_cops_cal_zipcode
    ////////////////////////////////////
    /**
     * @since v1.23.09.16
     */
    public function getZipCodes(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsZc), $this->dbTableZc);
        $request .= " WHERE zip LIKE '%s' AND primaryCity LIKE '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalZipCodeClass(), $request, $attributes);
    }

    ////////////////////////////////////
    // wp_7_cops_cal_phone
    ////////////////////////////////////
    /**
     * @since v1.23.09.16
     */
    public function getPhones(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsPh), $this->dbTablePh);
        $request .= " WHERE id LIKE '%s' AND phoneId LIKE '%s' AND cityName LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsCalPhoneClass(), $request, $attributes);
    }

}
