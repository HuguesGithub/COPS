<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsSkillClass;
use core\domain\CopsSkillJointClass;
use core\domain\CopsSkillSpecClass;

/**
 * Classe CopsSkillDaoImpl
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.06.25
 */
class CopsSkillDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.06.23
     * @version v1.23.06.25
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_skill";
        $this->dbTable_css  = "wp_7_cops_skill_spec";
        $this->dbTable_csj  = "wp_7_cops_player_skill_joint";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [
            self::FIELD_ID,
            self::FIELD_SKILL_NAME,
            self::FIELD_SKILL_DESC,
            self::FIELD_SKILL_USES,
            self::FIELD_SPEC_LEVEL,
            self::FIELD_PAN_USABLE,
            self::FIELD_REFERENCE,
            self::FIELD_DEFAULT_ABILITY
        ];
        $this->dbFields_css  = [
            self::FIELD_ID,
            self::FIELD_SPEC_NAME,
            self::FIELD_SKILL_ID
        ];
        $this->dbFields_csj  = [
            self::FIELD_ID,
            self::FIELD_COPS_ID,
            self::FIELD_SKILL_ID,
            self::FIELD_SPEC_SKILL_ID,
            self::FIELD_SCORE,
        ];
        ////////////////////////////////////

        parent::__construct();

    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_PLAYER_SKILL
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getSkills(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' ";
        /*
            AND skillName LIKE '%s'
            AND skillDescription LIKE '%s' ;
            AND specLevel LIKE '%s'
            AND panUsable LIKE '%s' ;
        */
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_PLAYER_SKILL_JOINT
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.23
     * @version v1.23.06.25
     */
    public function getCopsSkillJoints(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_csj), $this->dbTable_csj);
        $request .= " WHERE copsId LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillJointClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_SKILL_SPEC
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getSpecSkills(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_css), $this->dbTable_css);
        $request .= " WHERE id LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillSpecClass(), $request, $attributes);
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
