<?php
namespace core\domain;

use core\bean\CopsSkillJointBean;
use core\domain\CopsSkillClass;
use core\domain\CopsSkillSpecClass;
use core\services\CopsSkillServices;

/**
 * Classe CopsSkillJointClass
 * @author Hugues
 * @since v1.23.06.25
 * @version v1.23.06.25
 */
class CopsSkillJointClass extends LocalDomainClass
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  protected $id;
  protected $copsId;
  protected $skillId;
  protected $specSkillId;
  protected $score;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @since v1.23.06.25
   * @version v1.23.06.25
   */
  public function __construct(array $attributes=[])
  {
    parent::__construct($attributes);
    $this->stringClass = 'core\domain\CopsSkillJointClass';
  }
  /**
   * @param array $row
   * @return CopsSkillJointClass
   * @since v1.23.06.25
   * @version v1.23.06.25
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsSkillJointClass(), $row); }

    /**
     * @since v1.23.04.25
     */
    public function getBean(): CopsSkillJointBean
    { return new CopsSkillJointBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    public function getSkill(): CopsSkillClass
    {
        $objSkillServices = new CopsSkillServices();
        $attributes[self::SQL_WHERE_FILTERS] = [self::FIELD_ID=>$this->skillId];
        $objsSkills = $objSkillServices->getSkills($attributes);
        return array_shift($objsSkills);
    }

    public function getSpecSkill(): CopsSkillSpecClass
    {
        $objSkillServices = new CopsSkillServices();
        $attributes[self::SQL_WHERE_FILTERS] = [self::FIELD_ID=>$this->specSkillId];
        $objsSpecSkill = $objSkillServices->getSpecSkills($attributes);
        return array_shift($objsSpecSkill);
    }
}
