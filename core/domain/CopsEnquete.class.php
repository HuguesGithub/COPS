<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEnquete
 * @author Hugues
 * @version 1.22.09.16
 * @since 1.22.09.16
 */
class CopsEnquete extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $nomEnquete;
  protected $idxEnqueteur;
  protected $idxDistrictAttorney;
  protected $resumeFaits;
  protected $descSceneDeCrime;
  protected $pistesDemarches;
  protected $notesDiverses;
  // Statut de l'enquête :
  // 0 : cold case
  // 1 : affaire en cours
  // 2 : affaire close
  protected $statutEnquete;
  protected $dStart;
  protected $dLast;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.09.16
   * @since 1.22.09.16
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsEnquete';
  }
  /**
   * @param array $row
   * @return CopsEnquete
   * @version 1.22.09.16
   * @since 1.22.09.16
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsEnquete(), $row); }

  /**
   * @return CopsEnqueteBean
   * @version 1.22.09.16
   * @since 1.22.09.16
   */
  public function getBean()
  { return new CopsEnqueteBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
