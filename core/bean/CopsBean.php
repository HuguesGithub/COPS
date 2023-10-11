<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsBean
 * @author Hugues
 * @since 1.22.09.23
 * @version v1.23.07.29
 */
class CopsBean extends UtilitiesBean
{
    public function __construct()
    {
        $this->urlOnglet = '/admin/?'.self::CST_ONGLET.'=';
    }

    /**
     * @since v1.23.07.29
     * @version v1.23.07.29
     */
    public static function getBuiltTableHeader(array $headerElement, array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        foreach ($headerElement as $element) {
            $classe = $element['classe'] ?? self::CSS_COL;
            if (isset($element['abbr'])) {
                $tag = HtmlUtils::getBalise('abbr', $element['label'], [self::ATTR_TITLE=>$element['abbr']]);
            } else {
                $tag = $element['label'];
            }
            $objTableauCell = new TableauCellHtmlBean($tag, self::TAG_TH, $classe);
            if (isset($element['sortable'])) {
                $queryArg[self::SQL_ORDER_BY] = $element['sortable'];
                $objTableauCell->ableSort($queryArg);
            }
            $objRow->addCell($objTableauCell);
        }

        $objHeader = new TableauTHeadHtmlBean();
        $objHeader->addRow($objRow);

        return $objHeader;
    }

    /**
     * @since v1.23.10.07
     */
    public function getMutualFilter(
        array $urlElements,
        string $selectedValue,
        string $strLabel,
        array $objs,
        string $field,
        string $filter): string
    {
        $strLis = '';
        if ($selectedValue!='' && $selectedValue!='%') {
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($strLabel, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
            $strLabel = $selectedValue;
        }

        $arrRef = [];
        foreach ($objs as $obj) {
            $value = $obj->getField($field);
            if (isset($arrRef[$value])) {
                continue;
            }
            $arrRef[$value] = '';
            $urlElements[$filter] = $value;
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($value, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
            if ($value==$selectedValue) {
                $strLabel = $value;
            }
        }
        $ulAttributes = [
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'height: 200px; overflow: auto;',
        ];
        $ul = HtmlUtils::getBalise(self::TAG_UL, $strLis, $ulAttributes);

        $btnAttributes = [
            self::ATTR_CLASS => ' btn_outline btn-sm dropdown-toggle',
            'aria-expanded' => false,
            'data-bs-toggle' => 'dropdown',
        ];
        $strButton = HtmlUtils::getButton($strLabel, $btnAttributes);

        $divAttributes = [
            self::ATTR_CLASS => 'dropdown dropup',
            self::ATTR_STYLE => 'position: absolute; margin-top: -17px;',
        ];
        return HtmlUtils::getDiv($strButton.$ul, $divAttributes);
    }

    /**
     * @since v1.23.10.07
     */
    public function getTownFilter($urlElements, $field, $selectedValue=''): string
    {
        $objServices = new CopsRandomGuyServices();
        $attributes = [
            self::SQL_ORDER_BY => $field,
        ];
        $objs = $objServices->getPhones($attributes);
        $strLabel = 'Ville';
        $filter = $field;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }
}
