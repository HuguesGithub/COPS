<?php
namespace core\domain;

use core\bean\CopsCalGuyPhoneBean;
use core\domain\CopsCalPhoneClass;
use core\services\CopsCalGuyPhoneServices;
use core\services\CopsCalPhoneServices;

/**
 * Classe CopsCalGuyPhoneClass
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalGuyPhoneClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $guyId;
    protected $phoneNumber;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.12.02
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsCalGuyPhoneClass';
    }

    /**
     * @since v1.23.12.02
     */
    public static function convertElement($row): CopsCalGuyPhoneClass
    { return parent::convertRootElement(new CopsCalGuyPhoneClass(), $row); }

    /**
     * @since v1.23.12.02
     */
    public function getBean(): CopsCalGuyPhoneBean
    { return new CopsCalGuyPhoneBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.12.02
     */
    public function getCalPhone(): CopsCalPhoneClass
    {
        $objServices = new CopsCalPhoneServices();
        $value = substr($this->phoneNumber, 0, 7);
        $objs = $objServices->getCalPhones([self::FIELD_PHONE_ID=>$value]);

        if (empty($objs)) {
            $obj = new CopsCalPhoneClass();
            $obj->setField(self::FIELD_CITY_NAME, 'Ville inconnue');
        } else {
            $obj = array_shift($objs);
        }
        return $obj;
    }

}
