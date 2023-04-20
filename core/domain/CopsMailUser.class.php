<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailUser
 * @author Hugues
 * @since 1.22.05.01
 * @version 1.22.05.01
 */
class CopsMailUser extends LocalDomain
{
  protected $id;
  protected $mail;
  protected $user;
  protected $copsId;

  //////////////////////////////////////////////////
  // GETTER & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @since 1.22.05.01
   * @version 1.22.05.01
   */
  public function __construct($attributes=[])
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsMailUser';
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
}
