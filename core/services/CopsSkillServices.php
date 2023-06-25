<?php
namespace core\services;

use core\daoimpl\CopsSkillDaoImpl;

/**
 * Classe CopsSkillServices
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.06.25
 */
class CopsSkillServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////

    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getSkills(array $attributes=[]): array
    {
        $this->Dao = new CopsSkillDaoImpl();

        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $id,
            self::FIELD_SKILL_NAME,
            self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];

        return $this->Dao->getSkills($prepAttributes);
    }

    /**
     * @since v1.23.06.23
     * @version v1.23.06.25
     */
    public function getCopsSkillJoints(array $attributes=[]): array
    {
        $this->Dao = new CopsSkillDaoImpl();

        $copsId = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_COPS_ID] ?? self::SQL_JOKER_SEARCH;
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $copsId,
            self::FIELD_ID,
            self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];

        return $this->Dao->getCopsSkillJoints($prepAttributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getSpecSkills(array $attributes=[]): array
    {
        $this->Dao = new CopsSkillDaoImpl();

        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $id,
            self::FIELD_SPEC_NAME,
            self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];

        return $this->Dao->getSpecSkills($prepAttributes);
    }






  /**
   * @param array $attributes [E|S]
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = [
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
      ];
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

  /*
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function getSkillSpecs($skillId)
  {
    $attributes = [];
    $attributes[self::SQL_WHERE_FILTERS] = [$skillId];

    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_SPEC_NAME;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }

    return $this->Dao->getCopsSkillSpecs($attributes);
  }

}
