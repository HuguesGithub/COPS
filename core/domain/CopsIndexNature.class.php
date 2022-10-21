<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndexNature
 * @author Hugues
 * @version 1.22.10.21
 * @since 1.22.10.21
 */
class CopsIndexNature extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $idIdxNature
   */
  public $idIdxNature;
  public $nomIdxNature;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.10.21
   * @since 1.22.10.21
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsIndexNature';
  }
  /**
   * @param array $row
   * @return CopsIndexNature
   * @version 1.22.10.21
   * @since 1.22.10.21
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsIndexNature(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  

}
