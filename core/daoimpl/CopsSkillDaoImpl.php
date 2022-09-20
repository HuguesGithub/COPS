<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsSkillDaoImpl
 * @author Hugues
 * @since 1.22.05.30
 * @version 1.22.05.30
 */
class CopsSkillDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    $this->ObjClass = new CopsSkill();
    $this->dbTable  = "wp_7_cops_skill";
    ////////////////////////////////////

    parent::__construct();

    ////////////////////////////////////
    // Personnalisation de la requête avec les filtres
    $this->whereFilters .= "AND id LIKE '%s' AND skillName LIKE '%s' AND skillDescription LIKE '%s' AND specLevel LIKE '%s' AND panUsable LIKE '%s' ";
    ////////////////////////////////////
  }

  public function getCopsSkills($attributes)
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
        $Items[] = CopsSkill::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

  public function getCopsSkillSpecs($attributes)
  {
    $request  = vsprintf("SELECT id, specName, skillId FROM wp_7_cops_skill_spec WHERE skillId = '%s' ", $attributes[self::SQL_WHERE_FILTERS]);
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
        $Items[] = CopsSkillSpec::convertElement($row);
      }
    }
    return $Items;
    //////////////////////////////
  }

}
