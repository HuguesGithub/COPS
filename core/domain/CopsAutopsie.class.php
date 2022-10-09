<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsAutopsie
 * @author Hugues
 * @version 1.22.10.09
 * @since 1.22.10.09
 */
class CopsAutopsie extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $idxEnquete;
  protected $data;
  protected $dStart;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.10.09
   * @since 1.22.10.09
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsAutopsie';
  }
  /**
   * @param array $row
   * @return CopsAutopsie
   * @version 1.22.10.09
   * @since 1.22.10.09
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsAutopsie(), $row); }

  /**
   * @return CopsAutopsieBean
   * @version 1.22.10.09
   * @since 1.22.10.09
   */
  public function getBean()
  { return new CopsAutopsieBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
