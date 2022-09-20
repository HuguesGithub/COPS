<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class LocalDomain extends GlobalDomain implements ConstantsInterface
{
  /**
   * @param array $attributes
   * @since 1.22.05.08
   * @version 1.22.05.08
   */
  public function __construct($attributes=array())
  { parent::__construct($attributes); }

}
