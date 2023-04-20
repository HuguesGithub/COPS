<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEventBean
 * @author Hugues
 * @since 1.22.06.16
 * @version 1.22.06.16
 */
class CopsEventBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = ($objStd==null ? new CopsEvent() : $objStd);
        $this->urlOnglet   .= self::ONGLET_CALENDAR;
        $this->urlSubOnglet = $this->urlOnglet . '&amp;' . self::CST_SUBONGLET . '=';
    }

    /**
     * @since 1.22.06.16
     * @version 1.22.09.23
     */
    public function getTableRow()
    {
        $urlEdit  = $this->urlSubOnglet.self::CST_CAL_EVENT.'&amp;id='.$this->obj->getField(self::FIELD_ID);
        $urlEdit .= self::CST_AMP.self::CST_ACTION.'='.self::CST_WRITE;
        $urlTemplate = self::PF_TR_EVENT;
        $attributes = [
            // L'url vers le détail de l'event
            $urlEdit,
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
