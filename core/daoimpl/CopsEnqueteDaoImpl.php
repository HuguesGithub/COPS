<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEnqueteDaoImpl
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.16
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
   * @param array $attributes
   * @return array [CopsEnquete]
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
	public function getEnquetes($attributes)
	{
		////////////////////////////////////
		// Récupération des champs de l'objet en base
		$arrFields = array();
		$rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable.";");
		foreach($rows as $row) {
		  $arrFields[] = $row->Field;
		}
		////////////////////////////////////

		$request  = "SELECT ";
		foreach ($arrFields as $field) {
		  $request .= $field.", ";
		}
		$request = substr($request, 0, -2)." FROM ".$this->dbTable." ";
		$request .= "WHERE 1=1 AND statutEnquete LIKE '%s' ";
		$request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
		$prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
		echo $prepRequest;
		//////////////////////////////
		// Exécution de la requête
		$rows = MySQL::wpdbSelect($prepRequest);
		//////////////////////////////

		//////////////////////////////
		// Construction du résultat
		$Items = array();
		if (!empty($rows)) {
			foreach ($rows as $row) {
				$Items[] = CopsEnquete::convertElement($row);
			}
		}
		return $Items;
		//////////////////////////////
	}
	

  /**
   * @since 1.22.05.10
   * @version 1.22.05.10
   */
  public function updateEnquete($Obj)
  {
    ////////////////////////////////////
    // Récupération des champs de l'objet en base
    $arrFields = array();
    $rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable.";");
    foreach($rows as $row) {
      $arrFields[] = $row->Field;
    }
    ////////////////////////////////////

    $prepObject = array();
    $request  = "UPDATE ".$this->dbTable." SET ";
    foreach ($arrFields as $field) {
      $request .= $field."='%s', ";
      $prepObject[] = $Obj->getField($field);
    }
    $request = substr($request, 0, -2)." WHERE id = '%s';";
    $prepObject[] = $Obj->getField(self::FIELD_ID);

    $sql = MySQL::wpdbPrepare($request, $prepObject);
    echo "[[$sql]]";
    MySQL::wpdbQuery($sql);
  }
  ////////////////////////////////////

  /**
   * @since 1.22.05.10
   * @version 1.22.05.10
   */
  public function insertEnquete(&$Obj)
  {
    ////////////////////////////////////
    // Récupération des champs de l'objet en base
    $arrFields = array();
    $rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable.";");
    foreach($rows as $row) {
      if ($row->Field=='id') {
        continue;
      }
      $arrFields[] = $row->Field;
    }
    ////////////////////////////////////

    $prepObject = array();
    $request  = "INSERT INTO ".$this->dbTable." (";
    $requestValues = '';
    foreach ($arrFields as $field) {
      $request        .= $field.", ";
      $requestValues  .= "'%s', ";
      $prepObject[] = $Obj->getField($field);
    }
    $request = substr($request, 0, -2).") VALUES (".substr($requestValues, 0, -2).");";

    $sql = MySQL::wpdbPrepare($request, $prepObject);
    echo "[[$sql]]";
    MySQL::wpdbQuery($sql);
    $Obj->setField(self::FIELD_ID, MySQL::getLastInsertId());
  }


	
}
