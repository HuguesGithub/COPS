<?php
namespace core\bean;

use core\domain\CopsCalAddressClass;
use core\services\CopsCalGuyServices;
use core\services\CopsCalAddressServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * CopsCalGuyAddressBean
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyAddressBean extends CopsBean
{
    private $obj;
    private $objServices;
    private $objAddressServices;

    /**
     * @since v1.23.11.25
     */
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
        $this->initServices();
    }

    /**
     * @since v1.23.11.25
     */
    private function initServices()
    {
        $this->objServices = new CopsCalGuyServices();
        $this->objAddressServices = new CopsCalAddressServices();
    }

    /**
     * @since v1.23.11.25
     */
    public function getListAddress(): string
    {
        $obj = $this->getCalGuyAddress();
        $str = $this->obj->getField(self::FIELD_NUMBER).' '.$obj->getFullAddress();
        return HtmlUtils::getBalise(self::TAG_LI, $str);
    }

    /**
     * @since v1.23.11.25
     */
    public function getCalGuyAddress(): CopsCalAddressClass
    {
        $attributes = [self::FIELD_ID=>$this->obj->getField(self::FIELD_ADDRESS_ID)];
        $objs = $this->objAddressServices->getCalAddresses($attributes);
        return empty($objs) ? new CopsCalAddressClass() : array_shift($objs);
    }


}
