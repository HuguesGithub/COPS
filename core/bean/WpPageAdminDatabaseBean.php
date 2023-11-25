<?php
namespace core\bean;

use core\domain\CopsCalGuyClass;
use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminDatabaseBean
 * @author Hugues
 * @since v1.23.11.25
 */
class WpPageAdminDatabaseBean extends WpPageAdminBean
{
    /**
     * @since v1.23.11.25
     */
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Ajout du BreadCrumb
        $this->urlAttributes[self::CST_ONGLET] = self::ONGLET_BDD;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_DATABASES,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK]);
        /////////////////////////////////////////
    }

    /**
     * @since v1.23.06.20
     * @version v1.23.06.25
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        return match ($slugSubContent) {
            self::CST_BDD_SEARCH => new WpPageAdminDatabaseSearchBean(),
            self::CST_BDD_RESULT => new WpPageAdminDatabaseResultBean(),
            default => new WpPageAdminDatabaseSearchBean(),
        };
    }


    /**
     * @since v1.23.11.25
     */
    public function getInputContent(string $field, array $extraAttributes=[]): string
    {
        $filterValue = $this->filters[$field] ?? '';
        if ($filterValue==self::SQL_JOKER_SEARCH) {
            $filterValue = '';
        }
        $attributes = [
            self::ATTR_TYPE  => 'text',
            self::ATTR_CLASS => 'form-control col',
            self::FIELD_ID => $field,
            self::ATTR_NAME => $field,
            self::ATTR_VALUE => $filterValue,
        ];
        return HtmlUtils::getBalise(self::TAG_INPUT, '', array_merge($attributes, $extraAttributes));
    }

    /**
     * @since v1.23.11.25
     */
    public function getSelectContent(string $field): string
    {
        $selValue = $this->filters[$field] ?? '';
        $objServices = new CopsCalGuyServices();
        $strContentSel = HtmlUtils::getOption('', '');
        $objs = $objServices->getDistinctCalGuyField($field);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField($field);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValue);
        }
        $attributes = [
            self::ATTR_CLASS => self::CSS_CUSTOM_SELECT.self::CSS_COL,
            self::ATTR_NAME  => $field,
            self::FIELD_ID   => $field,
        ];
        return HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
    }

}
