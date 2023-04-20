<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMail
 * @author Hugues
 * @since 1.22.05.08
 * @version 1.22.05.08
 */
class CopsMailFolder extends LocalDomain
{
  protected $id;
  protected $slug;
  protected $label;
  protected $icon;

  //////////////////////////////////////////////////
  // GETTER & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @since 1.22.05.08
   * @version 1.22.05.08
   */
  public function __construct($attributes=[])
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsMailFolder';
    $this->CopsMailServices = new CopsMailServices();
  }

  /**
   * @since 1.22.05.02
   * @version 1.22.05.02
   */
  public function getBean()
  { return new CopsMailFolderBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @since 1.22.05.02
   * @version 1.22.05.02
   */
  public function getNombreMailsNonLus()
  { return $this->CopsMailServices->getNombreMailsNonLus([self::FIELD_SLUG=>$this->slug]); }

  /**
   * @since 1.22.05.02
   * @version 1.22.05.04
   */
  public function getFolderMails()
  { return $this->CopsMailServices->getMailJoints([self::FIELD_SLUG=>$this->slug]); }

}
