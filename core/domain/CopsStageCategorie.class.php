<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsStageCategorie
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.03
 */
class CopsStageCategorie extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $stageCategorie;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.06.02
   * @since 1.22.06.02
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsStageCategorie';
    $this->CopsStageServices = new CopsStageServices();
  }
  /**
   * @param array $row
   * @return CopsSkill
   * @version 1.22.06.02
   * @since 1.22.06.02
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsStageCategorie(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  /*
   * @version 1.22.06.02
   * @since 1.22.06.02
   */
  public function getBean()
  { return new CopsStageCategorieBean($this); }

  /*
   * @version 1.22.06.03
   * @since 1.22.06.03
   */
  public function getCopsStages()
  {
    $attributes[self::SQL_WHERE_FILTERS] = array(
      self::FIELD_ID           => self::SQL_JOKER_SEARCH,
      self::FIELD_STAGE_CAT_ID => $this->id,
      self::FIELD_STAGE_LEVEL  => self::SQL_JOKER_SEARCH,
    );
     return $this->CopsStageServices->getCopsStages($attributes); }
}
