<?php
namespace core\services;

use core\daoimpl\CopsSkillDaoImpl;
use core\domain\CopsSkillJointClass;

/**
 * Classe CopsSkillServices
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.12
 */
class CopsSkillServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  public function __construct()
  {
    $this->initDao();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  private function initDao(): void
  {
      if ($this->objDao==null) {
          $this->objDao = new CopsSkillDaoImpl();
      }
  }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getSkills(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_SKILL_NAME,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getSkills($prepAttributes);
    }

    public function getPlayerSkill(int $id): CopsSkillJointClass
    {
        $objs = $this->getCopsSkills([self::FIELD_ID=>$id]);
        return empty($objs) ? new CopsSkillJointClass() : array_shift($objs);
    }

    /**
     * @since v1.23.06.23
     * @version v1.23.08.12
     */
    public function getCopsSkills(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_COPS_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getCopsSkills($prepAttributes);
    }

    /**
     * @since v1.23.08.19
     */
    public function insertPlayerSkill(CopsSkillJointClass &$obj): void
    { $this->objDao->insertPlayerSkill($obj); }

    /**
     * @since v1.23.08.19
     */
    public function updatePlayerSkill(CopsSkillJointClass $obj): void
    { $this->objDao->updatePlayerSkill($obj); }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getSpecSkills(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_SKILL_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_SPEC_NAME,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getSpecSkills($prepAttributes);
    }

}
