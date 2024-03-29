<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsCalPhoneBean
 * @author Hugues
 * @since v1.23.10.14
 * @version v1.23.12.02
 */
class CopsCalPhoneBean extends CopsBean
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
        if ($field==self::FIELD_CITY_NAME) {
            $value = $this->obj->getField($field);
        } else {
            $value = $this->obj->getField(self::FIELD_PHONE_ID);
            list($first, $second) = explode('-', $value);
            $value = $field==self::CST_PN_FIRST ? $first : $second;
        }
        $linkAttributes = [
            self::ATTR_DATA => [
                self::ATTR_DATA_TARGET    => '#'.$field,
                self::ATTR_DATA_TRIGGER   => self::AJAX_ACTION_CLICK,
                self::ATTR_DATA_AJAX      => 'phoneDropdown',
            ]
        ];
        $strLink = HtmlUtils::getLink($value, '#', 'dropdown-item ajaxAction', $linkAttributes);
        $liAttributes = [self::ATTR_DATA => [self::ATTR_VALUE => $value]];
        return HtmlUtils::getBalise(self::TAG_LI, $strLink, $liAttributes);
    }

    public function getEmptyRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $strContent = 'Aucune donnée ne correspond aux critères de recherche.';
        $attributes = [
            self::CST_COLSPAN => 2,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, 'text-start', $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.10.14
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $value = $this->obj->getField(self::FIELD_PHONE_ID);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, 'text-start'));
        $value = $this->obj->getField(self::FIELD_CITY_NAME);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, 'text-start'));

        return $objRow;
    }

    /**
     * @since v1.23.10.14
     */
    public function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            ['label' => 'Identifiant'],
            ['label' => 'Ville'],
        ];

        return $this->getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.10.14
     */
    public function getTableFooter(array $attributes): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_PHONE,
        ];
        $field = self::FIELD_CITY_NAME;
        $filter = $attributes[$field] ?? '';
        $rowContent = $this->getTownFilter($urlElements, $field, $filter);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));

        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }

}
