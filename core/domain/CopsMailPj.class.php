<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailPj
 * @author Hugues
 * @since 1.22.05.01
 * @version 1.22.05.01
 */
class CopsMailPj extends LocalDomain
{
  protected $id;
  protected $pj_title;
  protected $pj_type;
  protected $pj_size;

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
    $this->stringClass = 'CopsMailPj';
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
}
