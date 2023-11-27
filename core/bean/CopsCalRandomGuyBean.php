<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * CopsCalRandomGuyBean
 * @author Hugues
 * @since v1.23.09.16
 * @version v1.23.12.02
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

        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        $value = $this->obj->getField(self::FIELD_NAMESET);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, 'text-start'));
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
     * @version v1.23.12.02
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
        $objs = $objServices->getCalGuys($attributes);
        $strLabel = 'NameSet';
        $filter = self::FIELD_NAMESET;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }

    /**
     * @since v1.23.07.21
     * @version v1.23.07.22
     */
    public function getEditInterfaceAttributes(): array
    {
        $objServices = new CopsRandomGuyServices();

        if ($this->obj->getField(self::FIELD_ID)=='') {
            $this->obj->setField(self::FIELD_NAMESET, SessionUtils::fromGet(self::FIELD_NAMESET));
        }

        ///////////////////////////////////////////////////
        // Gestion de la liste déroulante du Titre
        $selValueTitle = $this->obj->getField(self::FIELD_TITLE);
        $strContentSel = HtmlUtils::getOption('', 0);
        $objs = $objServices->getDistinctGuyField(self::FIELD_TITLE);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField(self::FIELD_TITLE);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValueTitle);
        }
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-2',
            self::ATTR_NAME  => self::FIELD_TITLE,
            self::FIELD_ID   => self::FIELD_TITLE,
        ];
        $selTitle = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        ///////////////////////////////////////////////////

        ///////////////////////////////////////////////////
        // Gestion de la liste déroulante du Genre
        $selValueGenre = $this->obj->getField(self::FIELD_GENDER);
        $strContentSel = HtmlUtils::getOption('', 0);
        $objs = $objServices->getDistinctGuyField(self::FIELD_GENDER);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField(self::FIELD_GENDER);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValueGenre);
        }
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-2',
            self::ATTR_NAME  => self::FIELD_GENDER,
            self::FIELD_ID   => self::FIELD_GENDER,
        ];
        $selGenre = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        ///////////////////////////////////////////////////

        ///////////////////////////////////////////////////
        // Gestion de la liste déroulante de l'Ethnie
        $selValueEthnie = $this->obj->getField(self::FIELD_NAMESET);
        $strContentSel = HtmlUtils::getOption('', 0);
        $objs = $objServices->getDistinctGuyField(self::FIELD_NAMESET);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField(self::FIELD_NAMESET);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValueEthnie);
        }
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-2',
            self::ATTR_NAME  => self::FIELD_NAMESET,
            self::FIELD_ID   => self::FIELD_NAMESET,
        ];
        $selEthnie = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        ///////////////////////////////////////////////////

        return [
            // Identifiant
            $this->obj->getField(self::FIELD_ID),
            // Liste déroulante pour le titre
            $selTitle,
            // Le Prénom
            $this->obj->getField(self::FIELD_FIRSTNAME),
            // Le Nom
            $this->obj->getField(self::FIELD_LASTNAME),
            // Liste déroulante pour le genre
            $selGenre,
            // Liste déroulante pour l'ethnie
            $selEthnie,
            //
            '',
            //
            '',
            //
            '',
            //
            '',
            '','','','','',
        ];
    }

}
