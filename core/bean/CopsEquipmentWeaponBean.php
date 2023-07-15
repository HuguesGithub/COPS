<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEquipmentWeaponBean
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.07.15
 */
class CopsEquipmentWeaponBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.07.15
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_NOM_ARME),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // Le score de Précision
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_PR)));
        // Le score de Puissance
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_PU)));
        // Le score de Force d'Arrêt
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_FA)));
        // La valeur éventuelle de Rafale Courte
        $value = $this->obj->getField(self::FIELD_SCORE_VRC);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Portée
        $value = $this->obj->getField(self::FIELD_PORTEE);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value.'m' : '/'));
        // Valeur de Couverture
        $value = $this->obj->getField(self::FIELD_SCORE_VC);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Cadence de Tir
        $value = $this->obj->getField(self::FIELD_SCORE_CT);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Munitions
        $value = $this->obj->getField(self::FIELD_MUNITIONS);
        $objRow->addCell(new TableauCellHtmlBean($value!='' ? $value : '/'));
        // Le score de Dissimulation
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_DIS)));
        // Le Prix
        $objRow->addCell(new TableauCellHtmlBean('$'.$this->obj->getField(self::FIELD_PRIX), self::TAG_TD, 'text-end'));
        return $objRow;
    }
}
