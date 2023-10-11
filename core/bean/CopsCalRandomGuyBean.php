<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsCalRandomGuyBean
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsCalRandomGuyBean extends CopsBean
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
            self::CST_COLSPAN => 6,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, 'text-start', $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.09.16
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getFullName(), self::TAG_TD, 'text-start'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_NAMESET), self::TAG_TD, 'text-start'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_PHONENUMBER)));
        $adress = $this->obj->getField(self::FIELD_NBADRESS).' '.$this->obj->getField(self::FIELD_STADRESS);
        $objRow->addCell(new TableauCellHtmlBean($adress, self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_ZIPCODE), self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_CITY), self::TAG_TD, 'text-start'));

        return $objRow;
    }

    /**
     * @since v1.23.09.16
     */
    public function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            ['label' => 'Nom', 'classe' => 'col-3'],
            ['label' => 'NameSet',],
            ['label' => 'Tél', 'abbr' => 'Téléphone'],
            ['label' => 'Adresse'],
            ['label' => 'CP', 'abbr' => 'Code Postal'],
            ['label' => 'Ville', 'abbr' => 'Puissance'],
        ];

        return $this->getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.09.16
     */
    public function getTableFooter(array $attributes): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));

        $filterNameSet = $attributes[self::FIELD_NAMESET] ?? '';
        $rowContent = $this->getNameSetFilter($filterNameSet);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));

        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));

        $filterZipCode = $attributes[self::FIELD_ZIPCODE] ?? '';
        $rowContent = $this->getZipCodeFilter($filterZipCode);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_HOME,
        ];
        $field = self::FIELD_PRIMARY_CITY;
        $filter = $attributes[$field] ?? '';
        $rowContent = $this->getTownFilter($urlElements, $field, $filter);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));

        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }

    /**
     * @since v1.23.10.07
     */
    public function getZipCodeFilter($selectedValue=''): string
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_HOME,
        ];
        $objServices = new CopsRandomGuyServices();
        $field = self::FIELD_ZIP;
        $attributes = [
            self::SQL_ORDER_BY => $field,
        ];
        $objs = $objServices->getZipCodes($attributes);
        $strLabel = 'Code Postal';
        $filter = self::FIELD_ZIP;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }

    /**
     * @since v1.23.10.07
     */
    public function getNameSetFilter($selectedValue=''): string
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_HOME,
        ];
        $objServices = new CopsRandomGuyServices();
        $field = self::FIELD_NAMESET;
        $attributes = [
            self::SQL_ORDER_BY => $field,
        ];
        $objs = $objServices->getGuys($attributes);
        $strLabel = 'NameSet';
        $filter = self::FIELD_NAMESET;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }

}
