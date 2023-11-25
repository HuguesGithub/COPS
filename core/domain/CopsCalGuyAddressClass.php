<?php
namespace core\domain;

use core\bean\CopsCalGuyAddressBean;
use core\services\CopsCalGuyAddressServices;

/**
 * Classe CopsCalGuyAddressClass
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyAddressClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $guyId;
    protected $addressId;
    protected $number;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.11.25
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsCalGuyAddressClass';
    }

    /**
     * @since v1.23.11.25
     */
    public static function convertElement($row): CopsCalGuyAddressClass
    { return parent::convertRootElement(new CopsCalGuyAddressClass(), $row); }

    /**
     * @since v1.23.11.25
     */
    public function getBean(): CopsCalGuyAddressBean
    { return new CopsCalGuyAddressBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
