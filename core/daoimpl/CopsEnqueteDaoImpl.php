<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEnqueteDaoImpl
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.21
 */
class CopsEnqueteDaoImpl extends LocalDaoImpl
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @since 1.22.09.16
   * @version 1.22.09.16
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    $this->dbTable  = "wp_7_cops_enquete";
    ////////////////////////////////////

    parent::__construct();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
    /**
     * @since 1.22.09.21
     * @version 1.22.09.21
     */
    public function getEnquete($prepObject)
    {
        $request  = $this->getSelect($this->dbTable);
        $request .= "FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        $prepSql  = MySQL::wpdbPrepare($request, $prepObject);
        return MySQL::wpdbSelect($prepSql);
    }

    /**
     * @param boolean
     * @return array
     * @since 1.22.09.21
     * @version 1.22.09.21
     */
    private function getTableFields($blnSkipId=false)
    {
        ////////////////////////////////////
        // Récupération des champs de l'objet en base
        $arrFields = array();
        $rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable.";");
        foreach ($rows as $row) {
            if ($blnSkipId && $row->Field=='id') {
                continue;
            }
            $arrFields[] = $row->Field;
        }
        ////////////////////////////////////
        return $arrFields;
    }
    
    
  /**
   * @param array $attributes
   * @return array [CopsEnquete]
   * @since 1.22.09.20
   * @version 1.22.09.21
   */
    public function getEnquetes($attributes)
    {
        $arrFields = $this->getTableFields();

        $request  = "SELECT ";
        foreach ($arrFields as $field) {
          $request .= $field.", ";
        }
        $request = substr($request, 0, -2)." FROM ".$this->dbTable." ";
        $request .= "WHERE 1=1 AND statutEnquete LIKE '%s' ";
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
        $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQL::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        $objsItem = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objsItem[] = CopsEnquete::convertElement($row);
            }
        }
        return $objsItem;
        //////////////////////////////
    }
    

  /**
   * @since 1.22.05.10
   * @version 1.22.09.21
   */
  public function updateEnquete($objStd)
  {
      $arrFields = $this->getTableFields();

    $prepObject = array();
    $request  = "UPDATE ".$this->dbTable." SET ";
    foreach ($arrFields as $field) {
      $request .= $field."='%s', ";
      $prepObject[] = $objStd->getField($field);
    }
    $request = substr($request, 0, -2)." WHERE id = '%s';";
    $prepObject[] = $objStd->getField(self::FIELD_ID);

    $sql = MySQL::wpdbPrepare($request, $prepObject);
    MySQL::wpdbQuery($sql);
  }
  ////////////////////////////////////

  /**
   * @since 1.22.05.10
   * @version 1.22.09.21
   */
  public function insertEnquete(&$objStd)
  {
      $arrFields = $this->getTableFields(true);

    $prepObject = array();
    $request  = "INSERT INTO ".$this->dbTable." (";
    $requestValues = '';
    foreach ($arrFields as $field) {
      $request        .= $field.", ";
      $requestValues  .= "'%s', ";
      $prepObject[] = $objStd->getField($field);
    }
    $request = substr($request, 0, -2).") VALUES (".substr($requestValues, 0, -2).");";

    $sql = MySQL::wpdbPrepare($request, $prepObject);
    MySQL::wpdbQuery($sql);
    $objStd->setField(self::FIELD_ID, MySQL::getLastInsertId());
  }


    
}
