<?php
namespace core\bean;

use core\services\CopsCalAddressServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsCalAddressBean
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalAddressBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.12.02
     */
    public function getDropDownLi(string $field): string
    {
        $value = $this->obj->getField($field);
        $linkAttributes = [
            self::ATTR_DATA => [
                self::ATTR_DATA_TARGET    => '#'.$field,
                self::ATTR_DATA_TRIGGER   => self::AJAX_ACTION_CLICK,
                self::ATTR_DATA_AJAX      => 'addressDropdown',
            ]
        ];
        $strLink = HtmlUtils::getLink($value, '#', 'dropdown-item ajaxAction', $linkAttributes);
        $liAttributes = [self::ATTR_DATA => [self::ATTR_VALUE => $value]];
        return HtmlUtils::getBalise(self::TAG_LI, $strLink, $liAttributes);
    }
}
