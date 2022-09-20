<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsStageCapaciteSpeciale
 * @author Hugues
 * @version 1.22.07.06
 * @since 1.22.07.06
 */
class CopsStageCapaciteSpeciale extends LocalDomain
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
  protected $specDescription;
  protected $stageId;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.07.06
   * @since 1.22.07.06
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsStageCapaciteSpeciale';
  }
  /**
   * @param array $row
   * @return CopsStageCapaciteSpeciale
   * @version 1.22.07.06
   * @since 1.22.07.06
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsStageCapaciteSpeciale(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
