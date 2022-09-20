<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsSkillServices
 * @author Hugues
 * @since 1.22.05.30
 * @version 1.22.05.30
 */
class CopsSkillServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.05.30
   * @since 1.22.05.30
   */
  public function __construct()
  {
    $this->Dao = new CopsSkillDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $attributes [E|S]
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = array(
        // Id
        self::SQL_JOKER_SEARCH,
        // skillName
        self::SQL_JOKER_SEARCH,
        // skillDescription
        self::SQL_JOKER_SEARCH,
        // specLevel
        self::SQL_JOKER_SEARCH,
        // panUsable
        self::SQL_JOKER_SEARCH,
      );
    } else {
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_SKILL_NAME])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_SKILL_NAME] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_SKILL_DESC])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_SKILL_DESC] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_SPEC_LEVEL])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_SPEC_LEVEL] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_PAN_USABLE])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_PAN_USABLE] = self::SQL_JOKER_SEARCH;
      }
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_SKILL_NAME;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }
    if (!isset($attributes[self::SQL_LIMIT])) {
      $attributes[self::SQL_LIMIT] = -1;
    }
  }

  /**
   * @param array $attributes
   *    [mixed]   : champs de l'objet
   *    [orderby] : tri sur une colonne
   *    [order]   : sens du tri
   *    [limit]   : nombre d'éléments max
   * @return array CopsSkill
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function getCopsSkills($attributes=array())
  {
    $this->initFilters($attributes);
    return $this->Dao->getCopsSkills($attributes);
  }

  /*
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function getSkillSpecs($skillId)
  {
    $attributes[self::SQL_WHERE_FILTERS] = array($skillId);

    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_SPEC_NAME;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }

    return $this->Dao->getCopsSkillSpecs($attributes);
  }

}
