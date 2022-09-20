<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsSkillSpec
 * @author Hugues
 * @version 1.22.05.30
 * @since 1.22.05.30
 */
class CopsSkillSpec extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $specName;
  protected $skillId;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.05.30
   * @since 1.22.05.30
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsSkillSpec';
    $this->CopsSkillServices = new CopsSkillServices();
  }
  /**
   * @param array $row
   * @return CopsSkill
   * @version 1.22.05.30
   * @since 1.22.05.30
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsSkillSpec(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
