<?php
namespace core\bean;

use core\domain\CopsSkillClass;
use core\services\CopsSkillServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsSkillBean
 * @author Hugues
 * @since 1.22.05.30
 * @version v1.23.08.12
 */
class CopsSkillBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.08.12
     */
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        $headerElement = [
            ['label' => 'Nom', 'sortable' => self::FIELD_SKILL_NAME, 'classe' => 'col-3'],
            ['label' => 'Description'],
            ['label' => 'Spécialisation'],
            ['label' => 'Adrénaline'],
            ['label' => 'Référence'],
        ];
        return static::getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.08.12
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_SKILL,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_SKILL_NAME),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // Description
        $objRow->addCell(new TableauCellHtmlBean(
            $this->obj->getField(self::FIELD_SKILL_DESC),
            self::TAG_TD,
            'text-start'
        ));
        // Spécialisation
        $strSpecialisation = $this->obj->getField(self::FIELD_SPEC_LEVEL)!=0 ? 'Oui' : 'Non';
        $objRow->addCell(new TableauCellHtmlBean($strSpecialisation));
        // Adrénaline utilisable
        $strAdrenaline = $this->obj->getField(self::FIELD_PAD_USABLE)!=0 ? 'Oui' : 'Non';
        $objRow->addCell(new TableauCellHtmlBean($strAdrenaline));
        // Référence
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_REFERENCE)));

        return $objRow;
    }
}
