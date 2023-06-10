<?php
namespace core\bean;

use core\bean\TableauHtmlBean;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEventBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.05.21
 */
class CopsEventBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.06.11
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_EVENT_LIBELLE),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink));
        // La catégorie de l'event
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getCategorie()->getField(self::FIELD_CATEG_LIBELLE)));
        // La date de début de l'event
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_DATE_DEBUT)));
        // La date de fin
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_DATE_FIN)));
        // Est-ce un event qui se répète ?
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_REPEAT_STATUS)==1 ? 'Oui' : 'Non'));
        // Le nombre de dates associées à cet événement
        $objRow->addCell(new TableauCellHtmlBean(count($this->obj->getEventDates())));

        return $objRow;
    }
}
