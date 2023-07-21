<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEquipmentCarBean
 * @author Hugues
 * @since v1.23.07.19
 * @version v1.23.07.22
 */
class CopsEquipmentCarBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_VEH_LABEL),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // La Vitesse Maximale
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_SPEED)));
        // L'accélération
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_ACCELERE)));
        // Nombre d'occupants
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_PLACES)));
        // Le Prix
        $objRow->addCell(
            new TableauCellHtmlBean('$'.$this->obj->getField(self::FIELD_VEH_PRICE), self::TAG_TD, 'text-end')
        );
        // Les Points de Structure
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_PS)));
        // Spécial
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP));

        return $objRow;
    }
}
