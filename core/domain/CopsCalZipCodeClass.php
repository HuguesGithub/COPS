<?php
namespace core\domain;

use core\bean\CopsCalZipcodeBean;

/**
 * Classe CopsCalZipcodeClass
 * @author Hugues
 * @since v1.23.09.16
 * @version v1.23.11.25
 */
class CopsCalZipcodeClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $zip;
    protected $type;
    protected $decommissioned;
    protected $primaryCity;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.09.16
     * @version v1.23.11.25
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsCalZipcodeClass';
    }

    /**
     * @since v1.23.09.16
     * @version v1.23.11.25
     */
    public static function convertElement($row): CopsCalZipcodeClass
    { return parent::convertRootElement(new CopsCalZipcodeClass(), $row); }

    /**
     * @since v1.23.10.14
     * @version v1.23.11.25
     */
    public function getBean(): CopsCalZipcodeBean
    { return new CopsCalZipcodeBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
