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
 * @version v1.23.05.14
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
        $this->dbFields_ced  = [
            self::FIELD_ID,
            self::FIELD_EVENT_ID,
            self::FIELD_DSTART,
            self::FIELD_DEND,
            self::FIELD_TSTART,
            self::FIELD_TEND,
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
     * @param array $attributes
     * @return CopsEventCategorie[]
     */
    public function getCopsEventCategories($attributes)
    {
        $request  = "SELECT id, categorieLibelle, categorieCouleur FROM ".$this->dbTable_cec." ";
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
                $objItems[] = CopsEventCategorieClass::convertElement($row);
            }
        }
        return $objItems;
        //////////////////////////////
    }


    /**
     * @since v1.23.05.05
     * @version v1.23.05.14
     */
    public function getEventDates(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_ced), $this->dbTable_ced);
        $request .= " WHERE id LIKE '%s' AND dStart <= '%s' AND dEnd >= '%s'";
        $request .= " ORDER BY %s %s LIMIT %s";
        return $this->selectListDaoImpl(new CopsEventDateClass(), $request, $attributes);
    }
}
