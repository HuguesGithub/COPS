<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMail
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.05.01
 */
class CopsMail extends LocalDomain
{
  protected $id;
  protected $mail_subject;
  protected $mail_content;
  protected $mail_dateEnvoi;

  //////////////////////////////////////////////////
  // GETTER & SETTERS
  //////////////////////////////////////////////////
  /**
   * @since 1.22.04.30
   * @version 1.22.04.30
   */
  public function setLu($lu)
  { $this->lu = $lu; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.04.29
   * @since 1.22.04.29
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsMail';
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  public function getDateEnvoiFormate()
  {
    $Y = substr($this->mail_dateEnvoi, 0, 4);
    $m = substr($this->mail_dateEnvoi, 5, 2);
    $d = substr($this->mail_dateEnvoi, 8, 2);
    $h = substr($this->mail_dateEnvoi, 11, 2);
    $i = substr($this->mail_dateEnvoi, 14, 2);
    $s = substr($this->mail_dateEnvoi, 17, 2);
    return date('d M Y h:i A', mktime($h, $i, $s, $m, $d, $Y)); //15 Feb. 2015 11:03 PM
  }
}
