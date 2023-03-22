<?php
namespace core\domain;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndexTome
 * @author Hugues
 * @version 1.23.02.15
 * @since 1.23.02.15
 */
class CopsIndexTomeClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $idIdxTome;
    protected $nomIdxTome;
    protected $abrIdxTome;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.23.02.15
     * @since 1.23.02.15
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsIndexTomeClass';
    }

    /**
     * @param array $row
     * @return CopsIndexTomeClass
     * @version 1.23.02.15
     * @since 1.23.02.15
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsIndexTomeClass(), $row); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
  

}
