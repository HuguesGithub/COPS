<?php
namespace core\bean;

use core\domain\MySQLClass;
use core\domain\CopsPlayerClass;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsPlayerDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class CopsPlayerDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    //$this->ObjClass = new CopsPlayer();
    $this->dbTable  = "wp_7_cops_player";
    ////////////////////////////////////

    parent::__construct();

    ////////////////////////////////////
    // Personnalisation de la requête avec les filtres
    $this->whereFilters .= "AND id LIKE '%s' AND matricule LIKE '%s' AND password LIKE '%s' AND grade LIKE '%s' ";
    ////////////////////////////////////
  }

  public function getCopsPlayers($attributes)
  {
    //////////////////////////////
    // Construction de la requête
    $request  = $this->select.vsprintf($this->whereFilters, $attributes[self::SQL_WHERE_FILTERS]);
    // On trie la liste
    // TODO : si $attributes[self::SQL_ORDER_BY] est un array
    // vérifier que $attributes[self::SQL_ORDER] est bien un array aussi dont la taille correspond
    // et construit l'order by en adéquation
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER]." ";
    // On limite si nécessaire
    if ($attributes[self::SQL_LIMIT]!=-1) {
      $request .= "LIMIT ".$attributes[self::SQL_LIMIT]." ";
    }
    $request .= ";";
    //////////////////////////////

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsPlayerClass::convertElement($row);
      }
    }
    return $objsItem;
    //////////////////////////////
  }

  public function insert($obj)
  {
    $prepObject = $this->prepObject($obj);
    $this->createEditDeleteEntry($this->insert, $prepObject);
  }

  public function update($obj)
  {
    $prepObject = $this->prepObject($obj, true);
    $this->createEditDeleteEntry($this->update." WHERE id='%s';", $prepObject);
  }

  /**
   * Créé, Edite, Supprime une Entrée
   * @since 1.0.00
   */
  protected function createEditDeleteEntry($requete, $arrParams=array())
  {
    $sql = MySQLClass::wpdbPrepare($requete, $arrParams);
    MySQLClass::wpdbQuery($sql);
  }

  public function prepObject($obj, $isUpdate=false)
  {
    $arr = array();
    $vars = $obj->getClassVars();
    if (!empty($vars)) {
      foreach ($vars as $key => $value) {
        if ($key=='id' || $key=='stringClass') {
            continue;
        }
        $arr[] = $obj->getField($key);
      }
      if ($isUpdate) {
          $arr[] = $obj->getField('id');
      }
    }
    return $arr;
  }

}
