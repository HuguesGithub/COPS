<?php
namespace core\domain;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndexNature
 * @author Hugues
 * @version 1.22.10.21
 * @since 1.22.10.21
 */
class CopsIndexNatureClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $idIdxNature;
    protected $nomIdxNature;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.10.21
     * @since 1.22.10.21
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsIndexNatureClass';
    }

    /**
     * @param array $row
     * @return CopsIndexNatureClass
     * @version 1.22.10.21
     * @since 1.22.10.21
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsIndexNatureClass(), $row); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
