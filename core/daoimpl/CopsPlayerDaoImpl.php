<?php
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
    $this->ObjClass = new CopsPlayer();
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
    $rows = MySQL::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CopsPlayer::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function insert($Obj)
  {
    $prepObject = $this->prepObject($Obj);
    $this->createEditDeleteEntry($this->insert, $prepObject);
  }

  public function update($Obj)
  {
    $prepObject = $this->prepObject($Obj, true);
    $this->createEditDeleteEntry($this->update." WHERE id='%s';", $prepObject);
  }

  /**
   * Créé, Edite, Supprime une Entrée
   * @since 1.0.00
   */
  protected function createEditDeleteEntry($requete, $arrParams=array())
  {
    $sql = MySQL::wpdbPrepare($requete, $arrParams);
    MySQL::wpdbQuery($sql);
  }

  public function prepObject($Obj, $isUpdate=false)
  {
    $arr = array();
    $vars = $Obj->getClassVars();
    if (!empty($vars)) {
      foreach ($vars as $key => $value) {
        if ($key=='id' || $key=='stringClass') {
            continue;
        }
        $arr[] = $Obj->getField($key);
      }
      if ($isUpdate) {
          $arr[] = $Obj->getField('id');
      }
    }
    return $arr;
  }

}
