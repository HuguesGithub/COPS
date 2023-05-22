<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsEventClass;
use core\domain\CopsEventDateClass;
use core\domain\CopsEventCategorieClass;
use core\services\CopsEventServices;

/**
 * Classe CopsEventDaoImpl
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.05.21
 */
class CopsEventDaoImpl extends LocalDaoImpl
{
    /**
     * Class constructor
     * @since 1.22.06.13
     * @version v1.23.05.07
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable  = "wp_7_cops_event";
        $this->dbTable_cec  = "wp_7_cops_event_categorie";
        $this->dbTable_ced  = "wp_7_cops_event_date";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields  = [
            self::FIELD_ID,
            self::FIELD_EVENT_LIBELLE,
            self::FIELD_CATEG_ID,
            self::FIELD_DATE_DEBUT,
            self::FIELD_DATE_FIN,
            self::FIELD_ALL_DAY_EVENT,
            self::FIELD_HEURE_DEBUT,
            self::FIELD_HEURE_FIN,
            self::FIELD_REPEAT_STATUS,
            self::FIELD_REPEAT_TYPE,
            self::FIELD_REPEAT_INTERVAL,
            self::FIELD_REPEAT_END,
            self::FIELD_REPEAT_END_VALUE,
        ];
        $this->dbFields_ced  = [
            self::FIELD_ID,
            self::FIELD_EVENT_ID,
            self::FIELD_DSTART,
            self::FIELD_DEND,
            self::FIELD_TSTART,
            self::FIELD_TEND,
        ];
        $this->dbFields_cec  = [
            self::FIELD_ID,
            self::FIELD_CATEG_LIBELLE,
            self::FIELD_CATEG_COLOR,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    public function getCopsEvents($attributes)
    {
        $request  = "SELECT id, eventLibelle, categorieId, dateDebut, dateFin, allDayEvent, heureDebut, ";
        $request .= "heureFin, repeatStatus, repeatType, repeatInterval, repeatEnd, repeatEndValue ";
        $request .= "FROM ".$this->dbTable." ";
        $request .= "WHERE 1=1 ";
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

        $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        $objItems = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsEventClass::convertElement($row);
            }
        }
        return $objItems;
        //////////////////////////////
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getCopsEvent($id)
    {
        $request  = "SELECT * FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";

        $prepRequest = vsprintf($request, [$id]);
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        return empty($rows) ? new CopsEventClass() : CopsEventClass::convertElement($rows[0]);
        //////////////////////////////
    }

    public function saveEvent($attributes)
    {
        $request  = "INSERT INTO ".$this->dbTable." (eventLibelle, categorieId, dateDebut, dateFin, allDayEvent, ";
        $request .= "heureDebut, heureFin, repeatStatus, repeatType, repeatInterval, repeatEnd, repeatEndValue) ";
        $request .= "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
        MySQLClass::wpdbQuery(MySQLClass::wpdbPrepare($request, $attributes));
        return MySQLClass::getLastInsertId();
    }

    public function saveEventDate($attributes)
    {
        $request  = "INSERT INTO ".$this->dbTable_ced." (eventId, dStart, dEnd, tStart, tEnd) ";
        $request .= "VALUES ('%s', '%s', '%s', '%s', '%s');";
        MySQLClass::wpdbQuery(MySQLClass::wpdbPrepare($request, $attributes));
        return MySQLClass::getLastInsertId();
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getCopsEventCategorie($id)
    {
        $request  = "SELECT id, categorieLibelle, categorieCouleur FROM ".$this->dbTable_cec." ";
        $request .= "WHERE id = '%s';";

        $prepRequest = vsprintf($request, [$id]);
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        return empty($rows) ? new CopsEventCategorieClass() : CopsEventCategorieClass::convertElement($rows[0]);
        //////////////////////////////
    }



    /**
     * @since v1.23.05.05
     * @version v1.23.05.21
     */
    public function getEventDates(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_ced), $this->dbTable_ced);
        $request .= " WHERE id LIKE '%s' AND dStart <= '%s' AND dEnd >= '%s'";
        $request .= " ORDER BY %s %s LIMIT %s";
        return $this->selectListDaoImpl(new CopsEventDateClass(), $request, $attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function deleteEventDate(array $attributes): void
    {
        $request  = "DELETE FROM ".$this->dbTable_ced;
        $request .= " WHERE id LIKE '%s' AND eventId LIKE '%s';";

        $prepRequest = vsprintf($request, $attributes);

        //////////////////////////////
        // Exécution de la requête
        $this->traceRequest($prepRequest);
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function insertEventDate(array $attributes): void
    {
        $fields = $this->dbFields_ced;
        array_shift($fields);

        $request  = "INSERT INTO ".$this->dbTable_ced." (";
        $request .= implode(', ', $fields);
        $request .= ") VALUES ('%s', '%s', '%s', '%s', '%s');";

        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        $this->traceRequest($prepRequest);
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getEvents(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND dateDebut <= '%s' AND dateFin >= '%s'";
        $request .= " ORDER BY %s %s LIMIT %s";
        return $this->selectListDaoImpl(new CopsEventClass(), $request, $attributes);
    }
    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getEventCategories(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_cec), $this->dbTable_cec);
        $request .= " WHERE id LIKE '%s'";
        $request .= " ORDER BY %s %s LIMIT %s";
        return $this->selectListDaoImpl(new CopsEventCategorieClass(), $request, $attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function updateEvent($objEvent)
    {
        $request = "UPDATE ".$this->dbTable." SET ";
        foreach ($this->dbFields as $field) {
            if ($field==self::FIELD_ID) {
                continue;
            }
            $request .= $field." = '".str_replace("'", "''", stripslashes($objEvent->getField($field)))."', ";
        }
        $request = substr($request, 0, -2)." WHERE id = ".$objEvent->getField(self::FIELD_ID);

        //////////////////////////////
        // Exécution de la requête
        $this->traceRequest($request);
        MySQLClass::wpdbQuery($request);
        //////////////////////////////
    }

}
