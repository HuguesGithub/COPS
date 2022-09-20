<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsStage
 * @author Hugues
 * @version 1.22.06.03
 * @since 1.22.06.03
 */
class CopsStage extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $stageCategorieId;
  protected $stageLibelle;
  protected $stageNiveau;
  protected $stageReference;
  protected $stagePreRequis;
  protected $stageCumul;
  protected $stageDescription;
  protected $stageBonus;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.06.03
   * @since 1.22.06.03
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsStage';
  }
  /**
   * @param array $row
   * @return CopsStage
   * @version 1.22.06.03
   * @since 1.22.06.03
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsStage(), $row); }
  /**
   * @return CopsStageBean
   * @version 1.22.06.03
   * @since 1.22.06.03
   */
  public function getBean()
  { return new CopsStageBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
