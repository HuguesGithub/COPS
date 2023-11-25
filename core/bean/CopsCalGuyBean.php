<?php
namespace core\bean;

use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * CopsCalGuyBean
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyBean extends CopsBean
{
    private $obj;
    private $objServices;
    private $nbFieldsPerRow;

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
    }

    //////////////////////////////////////////////////////
    // TABLE METHODS
    //////////////////////////////////////////////////////
    /**
     * @since v1.23.11.25
     */
    public function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            [self::FIELD_LABEL => self::LABEL_NOM, self::ATTR_CLASS => ' col-9'],
            [self::FIELD_LABEL => self::LABEL_BIRTHDAY, self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => self::LABEL_WEIGHT, self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => self::LABEL_HEIGHT, self::ATTR_CLASS => self::CSS_COL_1],
        ];
        $this->nbFieldsPerRow = 4;

        return $this->getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.11.25
     */
    public function getTooMuchRows(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $strContent = self::LABEL_SEARCH_TOO_MUCH_DATA;
        $attributes = [
            self::CST_COLSPAN => $this->nbFieldsPerRow,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, self::CSS_TEXT_START, $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.11.25
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
     * @since v1.23.11.25
     */
    public function getTableRow(bool $adminView=false): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
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
        // Date de naissance
        $value = $this->obj->getField(self::FIELD_BIRTHDAY);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));
        // Date de naissance
        $value = $this->obj->getField(self::FIELD_KILOGRAMS).'kg';
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));
        // Date de naissance
        $cms = $this->obj->getField(self::FIELD_CENTIMETERS);
        $taille = str_replace('.', 'm', str_pad($cms/100, 4, '0'));
        $objRow->addCell(new TableauCellHtmlBean($taille, self::TAG_TD, self::CSS_TEXT_END));

        return $objRow;
    }

    /**
     * @since v1.23.11.25
     */
    public function getTableFooter(array $attributes): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle(self::CSS_LINE_HEIGHT_30PX);
        // Pas de filtre sur le nom
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre sur la date de naissance
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Filtre sur le Nameset
        /*
        $filterNameSet = $attributes[self::FIELD_NAMESET] ?? '';
        $rowContent = $this->getNameSetFilter($filterNameSet);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));
        */
        // On ajoute le footer
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }
    //////////////////////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getNameSetFilter($selectedValue=''): string
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_BDD,
            self::CST_SUBONGLET => self::CST_BDD_RESULT,
        ];
        $field = self::FIELD_NAMESET;
        $attributes = [
            self::SQL_ORDER_BY => $field,
        ];
        $objs = $this->objServices->getCalGuys($attributes);
        $strLabel = ucfirst(self::FIELD_NAMESET);
        $filter = self::FIELD_NAMESET;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }

    /**
     * @since v1.23.11.25
     */
    public function getDetailInterface(bool $readonly=false): array
    {
        $spanCol3 = [self::ATTR_CLASS=>'input-group-text col-3'];
        $spanCol5 = [self::ATTR_CLASS=>'input-group-text col-5'];
        $inpAttributes = $readonly ? [self::CST_READONLY=>self::CST_READONLY] : [];

        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_CIVILITE, $spanCol3);
        $inpTitle = $span.$this->getSelectContent(self::FIELD_TITLE, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_FIRST_NAME, $spanCol3);
        $inpFirstName = $span.$this->getInputContent(self::FIELD_FIRSTNAME, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_NOM, $spanCol3);
        $inpLastName = $span.$this->getInputContent(self::FIELD_LASTNAME, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_BIRTHDAY, $spanCol5);
        $inpBirthday = $span.$this->getInputContent(self::FIELD_BIRTHDAY, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_WEIGHT, $spanCol3);
        $poids = $this->obj->getField(self::FIELD_KILOGRAMS);
        if ($poids!='') {
            $poids .= 'kg';
        }
        $inpKilograms = $span.$this->getInputContent(self::FIELD_KILOGRAMS, $inpAttributes, $poids);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_HEIGHT, $spanCol3);
        $cms = $this->obj->getField(self::FIELD_CENTIMETERS);
        if ($cms!='') {
            $taille = str_replace('.', 'm', str_pad($cms/100, 4, '0'));
        } else {
            $taille = '';
        }
        $inpCentimeters = $span.$this->getInputContent(self::FIELD_CENTIMETERS, $inpAttributes, $taille);
        /////////////////////////////////////////

        return [
            $inpTitle,
            $inpFirstName,
            $inpLastName,
            $inpBirthday,
            $inpKilograms,
            $inpCentimeters,
        ];
    }

    /**
     * @since v1.23.11.25
     */
    public function getSelectContent($field, array $extraAttributes=[])
    {
        $selValue = $this->obj->getField($field);
        $strContentSel = HtmlUtils::getOption();
        $objs = $this->objServices->getDistinctCalGuyField($field);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField($field);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValue);
        }
        $attributes = [
            self::ATTR_CLASS => self::CSS_CUSTOM_SELECT,
            self::ATTR_NAME  => $field,
            self::FIELD_ID   => $field,
        ];
        return HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, array_merge($attributes, $extraAttributes));
    }

    /**
     * @since v1.23.11.25
     */
    public function getInputContent(string $field, array $extraAttributes=[], $inpValue=''): string
    {
        if ($inpValue=='') {
            $inpValue = $this->obj->getField($field);
        }
        if ($inpValue==self::SQL_JOKER_SEARCH) {
            $inpValue = '';
        }
        $attributes = [
            self::ATTR_TYPE  => 'text',
            self::ATTR_CLASS => 'form-control col',
            self::FIELD_ID => $field,
            self::ATTR_NAME => $field,
            self::ATTR_VALUE => $inpValue,
        ];
        return HtmlUtils::getBalise(self::TAG_INPUT, '', array_merge($attributes, $extraAttributes));
    }

}
