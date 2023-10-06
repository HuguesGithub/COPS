<?php
namespace core\bean;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsRandomGuyBean
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsRandomGuyBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
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
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
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

        return static::getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.09.16
     */
    public static function getTableFooter(array $filters): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        /*
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $rowContent = static::getCategorieFilter($filterCateg);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));
        */
        for ($i=0; $i<6; $i++) {
            $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        }
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }
}
