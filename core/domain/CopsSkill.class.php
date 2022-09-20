<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsSkill
 * @author Hugues
 * @version 1.22.05.30
 * @since 1.22.05.30
 */
class CopsSkill extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $skillName;
  protected $skillDescription;
  protected $skillUses;
  protected $specLevel;
  protected $padUsable;
  protected $reference;
  protected $defaultAbility;

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
    $this->stringClass = 'CopsSkill';
  }
  /**
   * @param array $row
   * @return CopsSkill
   * @version 1.22.05.30
   * @since 1.22.05.30
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsSkill(), $row); }
  /**
   * @return CopsSkillBean
   * @version 1.22.05.30
   * @since 1.22.05.30
   */
  public function getBean()
  { return new CopsSkillBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  /**
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function getSpecialisations()
  {
    $this->CopsSkillServices = new CopsSkillServices();
    return $this->CopsSkillServices->getSkillSpecs($this->id);
  }
}
