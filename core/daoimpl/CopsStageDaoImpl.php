<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsStageDaoImpl
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.02
 */
class CopsStageDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.06.02
   * @version 1.22.06.02
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    //$this->ObjClass = new CopsStage();
    $this->dbTable  = "wp_7_cops_stage";
    $this->dbTable_csc  = "wp_7_cops_stage_categorie";
    $this->dbTable_css  = "wp_7_cops_stage_spec";
    ////////////////////////////////////

    parent::__construct();

    ////////////////////////////////////
    // Personnalisation de la requête avec les filtres
    $this->whereFilters .= "AND id LIKE '%s' AND stageCategorieId LIKE '%s' AND stageNiveau LIKE '%s' ";
    ////////////////////////////////////
  }

  public function getCopsStages($attributes)
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
        $Items[] = CopsStage::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function getCopsStageCategories($attributes)
  {
    $request  = "SELECT id, stageCategorie FROM ".$this->dbTable_csc." ";
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CopsStageCategorie::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function getCopsStageSpecs($attributes)
  {
    $whereFilters = "WHERE 1=1 AND stageId = '%s' ";


    $request  = "SELECT id, specName, specDescription, stageId FROM ".$this->dbTable_css." ";
    $request .= vsprintf($whereFilters, $attributes[self::SQL_WHERE_FILTERS]);
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQL::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CopsStageCapaciteSpeciale::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

}
