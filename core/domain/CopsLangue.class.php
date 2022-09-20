<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsLangue
 * @author Hugues
 * @version 1.22.04.28
 * @since 1.22.04.28
 */
class CopsLangue extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   *
   * @var string $libelle
   */
  protected $libelle;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function getLibelle()
  { return $this->libelle; }
  /**
   * @param int $id
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param string $libelle
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function setLibelle($libelle)
  { $this->libelle=$libelle; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsLangue';
  }
  /**
   * @param array $row
   * @return CopsLangue
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public static function convertElement($row)
  { return new CopsLangue($row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
