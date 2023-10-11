<?php
namespace core\domain;

use core\bean\CopsCalZipCodeBean;

/**
 * Classe CopsCalZipCodeClass
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsCalZipCodeClass extends LocalDomainClass
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
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsCalZipCodeClass';
    }

    /**
     * @since v1.23.09.16
     */
    public static function convertElement($row): CopsCalZipCodeClass
    { return parent::convertRootElement(new CopsCalZipCodeClass(), $row); }

    /**
     * @since v1.23.10.14
     */
    public function getBean(): CopsCalZipCodeBean
    { return new CopsCalZipCodeBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
