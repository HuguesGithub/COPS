<?php
namespace core\bean;

use core\domain\CopsCalPhoneClass;
use core\services\CopsCalGuyServices;
use core\services\CopsCalPhoneServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * CopsCalGuyPhoneBean
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalGuyPhoneBean extends CopsBean
{
    private $obj;
    private $objServices;
    private $objPhoneServices;

    /**
     * @since v1.23.12.02
     */
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
        $this->initServices();
    }

    /**
     * @since v1.23.12.02
     */
    private function initServices()
    {
        $this->objServices = new CopsCalGuyServices();
        $this->objPhoneServices = new CopsCalPhoneServices();
    }

    /**
     * @since v1.23.12.02
     */
    public function getListPhone(bool $edition=false): string
    {
        $str = $this->obj->getField(self::FIELD_PHONENUMBER);
        list($first, ,) = explode('-', $str);
        if ($first=='555') {
            $str .= ' (GSM)';
        } else {
            $obj = $this->obj->getCalPhone();
            $str .= ' ('.$obj->getField(self::FIELD_CITY_NAME).')';
        }
        if ($edition) {
            $strIcon = HtmlUtils::getIcon(self::I_DELETE);
            $href = '#';
            $strClasse = 'btn btn-danger btn-sm ajaxAction';
            $attributes = [
                self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
                self::ATTR_DATA => [
                    self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CLICK,
                    self::ATTR_DATA_AJAX => self::AJAX_DEL_GUY_PHONE
                ],
            ];
            $str .= ' '.HtmlUtils::getLink($strIcon, $href, $strClasse, $attributes);
        }
        return HtmlUtils::getBalise(self::TAG_LI, $str);
    }

}
