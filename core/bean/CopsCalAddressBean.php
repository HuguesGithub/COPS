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
    private $nbFieldsPerRow;

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

    /**
     * @since v1.23.12.02
     */
    public function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            [self::FIELD_LABEL => 'St Dir', self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => 'Street Name', self::ATTR_CLASS => ' col-6'],
            [self::FIELD_LABEL => 'St Suffixe', self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => 'St Suf Dir', self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => 'Zipcode', self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => 'min House Nb', self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => 'max House Nb', self::ATTR_CLASS => self::CSS_COL_1],
        ];
        $this->nbFieldsPerRow = 7;

        return $this->getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.12.02
     */
    public function getEmptyRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $strContent = self::LABEL_SEARCH_NO_DATA;
        $attributes = [
            self::CST_COLSPAN => $this->nbFieldsPerRow,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, self::CSS_TEXT_START, $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.12.02
     */
    public function getTableRow(bool $adminView=false): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        // Street Direction
        $value = $this->obj->getField(self::FIELD_ST_DIRECTION);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_START));

        // Street Name
        $value = '&nbsp;';//$this->obj->getField(self::FIELD_ST_DIRECTION);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));

        // Street Suffixe
        $value = $this->obj->getField(self::FIELD_ST_SUFFIX);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_START));

        // Street Suffixe Direction
        $value = $this->obj->getField(self::FIELD_ST_SUF_DIRECTION);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_START));

        // Street ZipCode
        $value = $this->obj->getField(self::FIELD_ZIPCODE);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));

        // Street MinHouseNumber
        $value = '&nbsp;';//$this->obj->getField(self::FIELD_ZIPCODE);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));

        // Street MaxHouseNumber
        $value = '&nbsp;';//$this->obj->getField(self::FIELD_ZIPCODE);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));

        /*
        // Lien vers le détail
        if ($adminView) {
            $urlElements = [
                self::CST_ONGLET => self::ONGLET_RND_GUY,
                self::CST_SUBONGLET => self::CST_HOME,
                self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
                self::CST_ACTION => self::CST_WRITE,
            ];
            $strLink = HtmlUtils::getLink(
                $this->obj->getFullName(),
                UrlUtils::getAdminUrl($urlElements),
            );

        } else {
            $urlElements = [
                self::WP_PAGE=>self::PAGE_ADMIN,
                self::CST_ONGLET => self::ONGLET_BDD,
                self::CST_SUBONGLET => self::CST_BDD_RESULT,
                self::FIELD_GENKEY => $this->obj->getField(self::FIELD_GENKEY),
            ];
            $strLink = HtmlUtils::getLink(
                $this->obj->getFullName(),
                UrlUtils::getPublicUrl($urlElements),
            );
        }
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, self::CSS_TEXT_START));
        */

        return $objRow;
    }
}
