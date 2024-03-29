<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsEventClass;
use core\domain\CopsEventDateClass;
use core\domain\CopsEventCategorieClass;
use core\services\CopsEventServices;
use core\utils\LogUtils;

/**
 * Classe CopsEventDaoImpl
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.08.12
 */
class CopsEventDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;
    private $dbTableCec;
    private $dbFieldsCec;
    private $dbTableCed;
    private $dbFieldsCed;

    /**
     * Class constructor
     * @since 1.22.06.13
     * @version v1.23.08.12
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable  = "wp_7_cops_event";
        $this->dbTableCec  = "wp_7_cops_event_categorie";
        $this->dbTableCed  = "wp_7_cops_event_date";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_EVENT_LIBELLE,
            self::FIELD_CATEG_ID,
            self::FIELD_DATE_DEBUT,
            self::FIELD_DATE_FIN,
            self::FIELD_ALL_DAY_EVENT,
            self::FIELD_CONTINU_EVENT,
            self::FIELD_HEURE_DEBUT,
            self::FIELD_HEURE_FIN,
            self::FIELD_REPEAT_STATUS,
            self::FIELD_REPEAT_TYPE,
            self::FIELD_REPEAT_INTERVAL,
            self::FIELD_REPEAT_END,
            self::FIELD_REPEAT_END_VALUE,
            self::FIELD_CUSTOM_EVENT,
            self::FIELD_CUSTOM_DAY,
            self::FIELD_CUSTOM_DAY_WEEK,
            self::FIELD_CUSTOM_MONTH,
        ];
        $this->dbFieldsCed = [
            self::FIELD_ID,
            self::FIELD_EVENT_ID,
            self::FIELD_DSTART,
            self::FIELD_DEND,
            self::FIELD_TSTART,
            self::FIELD_TEND,
        ];
        $this->dbFieldsCec = [
            self::FIELD_ID,
            self::FIELD_CATEG_LIBELLE,
            self::FIELD_CATEG_COLOR,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_event
    ////////////////////////////////////

    /**
     * @since v1.23.05.15
     * @version v1.23.06.18
     */
    public function getEvents(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND categorieId LIKE '%s' AND dateDebut <= '%s' AND dateFin >= '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEventClass(), $request, $attributes);
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function insertEvent(CopsEventClass &$objEvent): void
    {
        // On récupère les champs
        $fields = $this->dbFields;
        array_shift($fields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($objEvent, $fields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.06.04
     */
    public function updateEvent(CopsEventClass $objEvent)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($objEvent, $request, $fieldId);
    }

    /**
     * @since v1.23.05.25
     * @version v1.23.06.04
     */
    public function deleteEvent(array $attributes): void
    {
        $request  = "DELETE FROM ".$this->dbTable;
        $request .= " WHERE id LIKE '%s';";

        $prepRequest = vsprintf($request, $attributes);

        //////////////////////////////
        // Exécution de la requête
        LogUtils::logRequest($prepRequest);
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////
    }

    ////////////////////////////////////
    // wp_7_cops_event_date
    ////////////////////////////////////

    /**
     * @since v1.23.05.05
     * @version v1.23.08.12
     */
    public function getEventDates(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCed), $this->dbTableCed);
        $request .= " WHERE id LIKE '%s' AND eventId LIKE '%s' AND dStart <= '%s' AND dEnd >= '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEventDateClass(), $request, $attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.08.12
     */
    public function insertEventDate(array $attributes): int
    {
        // Ne peut pas être migré "à la insertDaoImpl" car le nom de la table n'est pas dans dbTable
        $fields = $this->dbFieldsCed;
        array_shift($fields);

        return $this->insert($this->dbTableCed, $fields, $attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.06.04
     */
    public function deleteEventDate(array $attributes): void
    {
        $request  = "DELETE FROM ".$this->dbTable_ced;
        $request .= " WHERE id LIKE '%s' AND eventId LIKE '%s';";

        $prepRequest = vsprintf($request, $attributes);

        //////////////////////////////
        // Exécution de la requête
        LogUtils::logRequest($prepRequest);
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////
    }

    ////////////////////////////////////
    // wp_7_cops_event_categorie
    ////////////////////////////////////

    /**
     * @since v1.23.05.15
     * @version v1.23.05.28
     */
    public function getEventCategories(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCec), $this->dbTableCec);
        $request .= " WHERE id LIKE '%s'".$this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsEventCategorieClass(), $request, $attributes);
    }

}
