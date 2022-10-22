<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndex
 * @author Hugues
 * @version 1.22.09.06
 * @since 1.22.09.06
 */
class CopsIndex extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $nomIdx;
  protected $natureId;
  protected $descriptionPJ;
  protected $descriptionMJ;
  protected $reference;
  protected $code;
  
  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.09.06
   * @since 1.22.09.06
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsIndex';
    
    $this->copsIndexServices = new CopsIndexServices();
  }
  /**
   * @param array $row
   * @return CopsIndex
   * @version 1.22.09.06
   * @since 1.22.09.06
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsIndex(), $row); }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getBean()
    { return new CopsIndexBean($this); }
    
  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

    public function checkFields()
    { return ($this->nomIdx!=''); }
    
  public function getNature()
  { return $this->copsIndexServices->getCopsIndexNature($this->natureId); }


  public function insertCopsIndex()
  {
      $requete  = "INSERT INTO wp_7_cops_index ";
      $requete .= "(nomIdx, natureId, reference, descriptionMJ, descriptionPJ) ";
      $requete .= "VALUES (";
      $requete .= "'".$this->nomIdx."', ";
      $requete .= "'".$this->natureId."', ";
      $requete .= "'".$this->reference."', ";
      $requete .= "'".$this->descriptionMJ."', ";
      $requete .= "'".$this->descriptionPJ."'";
      $requete .= ");";

      MySQL::wpdbQuery($requete);
  }


}
