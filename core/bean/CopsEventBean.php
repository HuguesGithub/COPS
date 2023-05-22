<?php
namespace core\bean;

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
     * @version v1.23.05.21
     */
    public function getTableRow()
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];

        $urlTemplate = self::WEB_PAFT_EVENT_ROW;
        $attributes = [
            // L'url vers le détail de l'event
            UrlUtils::getAdminUrl($urlElements),
            // Le libellé de l'event
            $this->obj->getField(self::FIELD_EVENT_LIBELLE),
            // La catégorie de l'event
            $this->obj->getCategorie()->getField(self::FIELD_CATEG_LIBELLE),
            // LA date de début de l'event
            $this->obj->getField(self::FIELD_DATE_DEBUT),
            // La date de fin
            $this->obj->getField(self::FIELD_DATE_FIN),
            // Est-ce un event qui se répète ?
            ($this->obj->getField(self::FIELD_REPEAT_STATUS)==1 ? 'Oui' : 'Non'),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
