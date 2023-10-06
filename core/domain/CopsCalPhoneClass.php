<?php
namespace core\domain;


/**
 * Classe CopsCalPhoneClass
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsCalPhoneClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $phoneId;
    protected $cityName;
    
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
        $this->stringClass = 'core\domain\CopsCalPhoneClass';
    }

    /**
     * @since v1.23.09.16
     */
    public static function convertElement($row): CopsCalPhoneClass
    { return parent::convertRootElement(new CopsCalPhoneClass(), $row); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
