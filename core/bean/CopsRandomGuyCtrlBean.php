<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsRandomGuyCtrlBean
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsRandomGuyCtrlBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    public function addPhoneColumns(TableauRowHtmlBean &$objRow): void
    {
        $objCalPhone = $this->obj->getCalPhone();
        $phoneId = $objCalPhone->getField(self::FIELD_PHONE_ID);
        $cityName = $objCalPhone->getField(self::FIELD_CITY_NAME);

        if ($phoneId==null) {
            $objServices = new CopsRandomGuyServices();
            $cityName = $this->obj->getField(self::FIELD_CITY);
            $objs = $objServices->getPhones([self::FIELD_CITY_NAME=>$cityName]);

            if (empty($objs)) {
                $phoneId = self::CST_NBSP;
                $cityName = self::CST_NBSP;
            } else {
                $selContent = HtmlUtils::getOption('Choisir...', -1, true);
                while (!empty($objs)) {
                    $obj = array_shift($objs);
                    $phoneId = $obj->getField(self::FIELD_PHONE_ID);
                    $selContent .= HtmlUtils::getOption($phoneId, $phoneId);
                }
                $phoneId = HtmlUtils::getBalise(self::TAG_SELECT, $selContent, [self::ATTR_CLASS=>'form-control']);

            }
        }

        $objRow->addCell(new TableauCellHtmlBean($phoneId, self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($cityName, self::TAG_TD, 'text-start'));
    }

    public function addZipCodeColumns(TableauRowHtmlBean &$objRow): void
    {
        $extraClass='';
        $objCalZipCode = $this->obj->getCalZipCode();
        $zip = $objCalZipCode->getField(self::FIELD_ZIP);
        if ($zip==null) {
            // On va faire une recherche avec le nom de la ville dans les zipCodes.
            $objServices = new CopsRandomGuyServices();
            $primaryCity = $this->obj->getField(self::FIELD_CITY);
            $objs = $objServices->getZipCodes([self::FIELD_PRIMARY_CITY=>$primaryCity]);

            if (empty($objs)) {
                $zip = self::CST_NBSP;
                $primaryCity = self::CST_NBSP;
            } else {
                $selContent = HtmlUtils::getOption('Choisir...', -1, true);
                while (!empty($objs)) {
                    $obj = array_shift($objs);
                    $zip = $obj->getField(self::FIELD_ZIP);
                    $selContent .= HtmlUtils::getOption($zip, $zip);
                }
                $zip = HtmlUtils::getBalise(self::TAG_SELECT, $selContent, [self::ATTR_CLASS=>'form-control']);
            }
        } else {
            $primaryCity = $objCalZipCode->getField(self::FIELD_PRIMARY_CITY);
            if ($primaryCity!=$this->obj->getField(self::FIELD_CITY)) {
                $extraClass = ' bg-warning';
            }
        }

        $objRow->addCell(new TableauCellHtmlBean($zip, self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($primaryCity, self::TAG_TD, 'text-start'.$extraClass));
    }

    /**
     * @since v1.23.09.16
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getFullName(), self::TAG_TD, 'text-start'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_PHONENUMBER)));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_NBADRESS), self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_STADRESS), self::TAG_TD, 'text-start'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_ZIPCODE), self::TAG_TD, 'text-end'));
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_CITY), self::TAG_TD, 'text-start'));

        $this->addZipCodeColumns($objRow);
        $this->addPhoneColumns($objRow);

        return $objRow;
    }

    /**
     * @since v1.23.09.16
     */
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            ['label' => 'Nom', 'classe' => 'col-3'],
            ['label' => 'Tél', 'abbr' => 'Téléphone'],
            ['label' => 'Num', 'abbr' => 'Numéro'],
            ['label' => 'Rue'],
            ['label' => 'CP', 'abbr' => 'Code Postal'],
            ['label' => 'Ville', 'abbr' => 'Puissance'],
            ['label' => 'CP', 'abbr' => 'zip'],
            ['label' => 'Ville', 'abbr' => 'primary_city'],
            ['label' => 'Pid', 'abbr' => 'phoneId'],
            ['label' => 'Ville', 'abbr' => 'cityName'],
        ];

        return static::getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.10.07
     */
    public static function getTableFooter(array $attributes=[]): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        for ($i=0; $i<4; $i++) {
            $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        }
        $filterZipCode = $attributes[self::FIELD_ZIPCODE];
        $rowContent = static::getZipCodeFilter($filterZipCode);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));
        for ($i=0; $i<5; $i++) {
            $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        }
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }


    /**
     * @since v1.23.10.07
     */
    public static function getZipCodeFilter($selectedValue=''): string
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_CONTROL,
        ];
        $strLabel = 'Code Postal';
        $strLis = '';
        if ($selectedValue!='') {
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($strLabel, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
        }

        $objServices = new CopsRandomGuyServices();
        $objs = $objServices->getZipCodes();
        foreach ($objs as $objZipCode) {
            $value = $objZipCode->getField(self::FIELD_ZIP);
            $urlElements['filterZipCode'] = $value;
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
}
