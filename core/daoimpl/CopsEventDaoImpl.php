<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEventDaoImpl
 * @author Hugues
 * @since 1.22.06.13
 * @version 1.22.06.13
 */
class CopsEventDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.06.13
   * @version 1.22.06.13
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    //$this->ObjClass = new CopsEvent();
    $this->dbTable  = "wp_7_cops_event";
    $this->dbTable_cec  = "wp_7_cops_event_categorie";
    $this->dbTable_ced  = "wp_7_cops_event_date";
    ////////////////////////////////////

    parent::__construct();
  }

  public function getCopsEventDates($attributes)
  {
    $request  = "SELECT id, eventId, dStart, dEnd, tStart, tEnd FROM ".$this->dbTable_ced." ";
    $request .= "WHERE 1=1 AND id LIKE '%s' AND dStart <= '%s' AND dEnd >= '%s' ";
    if (is_array($attributes[self::SQL_ORDER_BY])) {
        $request .= "ORDER BY ";
        while (!empty($attributes[self::SQL_ORDER_BY])) {
            $orderBy = array_shift($attributes[self::SQL_ORDER_BY]);
            $order = array_shift($attributes[self::SQL_ORDER]);
            $request .= $orderBy." $order, ";
        }
        $request = substr($request, 0, -2).";";
    } else {
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
    }

    $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($prepRequest);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CopsEventDate::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function getCopsEvents($attributes)
  {
    $request  = "SELECT id, eventLibelle, categorieId, dateDebut, dateFin, allDayEvent, heureDebut, heureFin, repeatStatus, repeatType, repeatInterval, repeatEnd, repeatEndValue FROM ".$this->dbTable." ";
    $request .= "WHERE 1=1 ";
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

    $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($prepRequest);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CopsEvent::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function getCopsEvent($id)
  {
    $request  = "SELECT * FROM ".$this->dbTable." ";
    $request .= "WHERE id = '%s';";

    $prepRequest = vsprintf($request, array($id));
    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($prepRequest);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    return (empty($rows) ? new CopsEvent() : CopsEvent::convertElement($rows[0]));
    //////////////////////////////
  }

  public function saveEvent($attributes)
  {
    $request  = "INSERT INTO ".$this->dbTable." (eventLibelle, categorieId, dateDebut, dateFin, allDayEvent, heureDebut, heureFin, repeatStatus, repeatType, repeatInterval, repeatEnd, repeatEndValue) ";
    $request .= "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
    MySQL::wpdbQuery(MySQL::wpdbPrepare($request, $attributes));
    return MySQL::getLastInsertId();
  }

  public function saveEventDate($attributes)
  {
    $request  = "INSERT INTO ".$this->dbTable_ced." (eventId, dStart, dEnd, tStart, tEnd) ";
    $request .= "VALUES ('%s', '%s', '%s', '%s', '%s');";
    MySQL::wpdbQuery(MySQL::wpdbPrepare($request, $attributes));
    return MySQL::getLastInsertId();
  }

  public function getCopsEventCategorie($id)
  {
    $request  = "SELECT id, categorieLibelle, categorieCouleur FROM ".$this->dbTable_cec." ";
    $request .= "WHERE id = '%s';";

    $prepRequest = vsprintf($request, array($id));
    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($prepRequest);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    return (empty($rows) ? new CopsEventCategorie() : CopsEventCategorie::convertElement($rows[0]));
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
      $rows = MySQL::wpdbSelect($prepRequest);
      //////////////////////////////
      
      //////////////////////////////
      // Construction du résultat
      $Items = array();
      if (!empty($rows)) {
          foreach ($rows as $row) {
              $Items[] = CopsEventCategorie::convertElement($row);
          }
      }
      return $Items;
      //////////////////////////////
  }
  
}
