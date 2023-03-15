<?php
namespace core\bean;

use core\domain\MySQLClass;
use core\domain\CopsStageClass;
use core\domain\CopsStageCapaciteSpecialeClass;
use core\domain\CopsStageCategorieClass;

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
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsStageClass::convertElement($row);
      }
    }
    return $objsItem;
    //////////////////////////////
  }

  public function getCopsStageCategories($attributes)
  {
    $request  = "SELECT id, stageCategorie FROM ".$this->dbTable_csc." ";
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsStageCategorieClass::convertElement($row);
      }
    }
    return $objsItem;
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
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsStageCapaciteSpecialeClass::convertElement($row);
      }
    }
    return $objsItem;
    //////////////////////////////
  }

}
