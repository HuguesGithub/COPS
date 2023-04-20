<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsSkillClass;
use core\domain\CopsSkillSpecClass;

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
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.05.30
     * @version 1.22.05.30
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_skill";
        $this->dbTable_css  = "wp_7_cops_skill_spec";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [self::FIELD_ID, self::FIELD_SKILL_NAME, self::FIELD_SKILL_DESC, self::FIELD_SKILL_USES, self::FIELD_SPEC_LEVEL, self::FIELD_PAN_USABLE, self::FIELD_REFERENCE, self::FIELD_DEFAULT_ABILITY];
        $this->dbFields_css  = [self::FIELD_ID, self::FIELD_SPEC_NAME, self::FIELD_SKILL_ID];
        ////////////////////////////////////

        parent::__construct();

    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_SKILL
    //////////////////////////////////////////////////
  
    /**
     * @param array $attributes
     * @return array [CopsSkill]
     * @since 1.23.03.18
     * @version 1.23.03.18
     */
    public function getSkills($attributes)
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND skillName LIKE '%s' AND skillDescription LIKE '%s' ";
        $request .= "AND specLevel LIKE '%s' AND panUsable LIKE '%s'";
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER]." ";
        // On limite si nécessaire
        if ($attributes[self::SQL_LIMIT]!=-1) {
          $request .= "LIMIT ".$attributes[self::SQL_LIMIT]." ";
        }
        $request .= ";";
        return $this->selectListDaoImpl(new CopsSkillClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_SKILL_SPEC
    //////////////////////////////////////////////////


  public function getCopsSkillSpecs($attributes)
  {
    $strSql = "SELECT id, specName, skillId FROM wp_7_cops_skill_spec WHERE skillId = '%s' ";
    $request  = vsprintf($strSql, $attributes[self::SQL_WHERE_FILTERS]);
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = [];
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsSkillSpecClass::convertElement($row);
      }
    }
    return $objsItem;
    //////////////////////////////
  }

}
