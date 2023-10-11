<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsCalZipCodeBean
 * @author Hugues
 * @since v1.23.10.14
 */
class CopsCalZipCodeBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
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
        $value = $this->obj->getField(self::FIELD_ZIP);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, 'text-start'));
        $value = $this->obj->getField(self::FIELD_PRIMARY_CITY);
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
            ['label' => 'Code Postal'],
            ['label' => 'Ville principale'],
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
            self::CST_SUBONGLET => self::CST_ZIPCODE,
        ];
        $field = self::FIELD_PRIMARY_CITY;
        $filter = $attributes[$field] ?? '';
        $rowContent = $this->getTownFilter($urlElements, $field, $filter);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));

        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }

}
