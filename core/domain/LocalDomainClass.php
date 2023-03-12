<?php
namespace core\domain;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class LocalDomainClass extends GlobalDomainClass
{
    /**
     * @param array $attributes
     * @since 1.22.05.08
     * @version 1.22.05.08
     */
    public function __construct($attributes=array())
    { parent::__construct($attributes); }
    
    /**
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getFields()
    { return get_class_vars($this->stringClass); }
}
