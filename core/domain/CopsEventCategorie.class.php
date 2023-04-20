<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEventCategorie
 * @author Hugues
 * @version 1.22.06.25
 * @since 1.22.06.25
 */
class CopsEventCategorie extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $categorieLibelle;
  protected $categorieCouleur;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.06.25
   * @since 1.22.06.25
   */
  public function __construct($attributes=[])
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsEventCategorie';
    $this->CopsEventServices = new CopsEventServices();
  }
  /**
   * @param array $row
   * @return CopsEventCategorie
   * @version 1.22.06.25
   * @since 1.22.06.25
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsEventCategorie(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
