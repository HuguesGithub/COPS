<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailJoint
 * @author Hugues
 * @since 1.22.05.01
 * @version 1.22.05.01
 */
class CopsMailJoint extends LocalDomain
{
  protected $id;
  protected $mailId;
  protected $toId;
  protected $fromId;
  protected $folderId;
  protected $lu;
  protected $nbPjs;

  //////////////////////////////////////////////////
  // GETTER & SETTERS
  //////////////////////////////////////////////////


  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.05.01
   * @since 1.22.05.01
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsMailJoint';
    $this->CopsMailServices = new CopsMailServices();
  }

  public function getBean()
  { return new CopsMailJointBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  public function getMailFolder()
  {
    $MailFolders = $this->CopsMailServices->getMailFolders(array(self::FIELD_ID=>$this->folderId));
    return array_shift($MailFolders);
  }
  public function getMail()
  { return $this->CopsMailServices->getMail($this->mailId); }

  public function getAuteur()
  { return $this->CopsMailServices->getMailUser($this->fromId); }
}
