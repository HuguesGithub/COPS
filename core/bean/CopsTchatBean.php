<?php
namespace core\bean;

use core\services\CopsPlayerServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;

/**
 * CopsTchatBean
 * @author Hugues
 * @since v1.23.08.05
 */
class CopsTchatBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.08.05
     */
    public function getTchatRow(): string
    {
        // A quoi veut-on que ressemble le message ?
        // On devrait mettre l'auteur du message si ce n'est pas soi-même
        // Et bien sûr, on doit afficher le contenu du message.
        // Et on inclu tout ça dans une div avec la date d'enregistrement, pour les besoins du refresh
        // <Date> - <Auteur> : <Msg>

        //////////////////////////////////////////////////////
        $strMsg = '[';
        // On ajoute la Date
        // Si c'est le jour même : [H:i]
        // Si c'est la veille ou plus : [d Déc H:i]
        $tsMsg = $this->obj->getField(self::FIELD_TIMESTAMP);
        $tsMonth = substr($tsMsg, 5, 2);
        $tsDay = substr($tsMsg, 8, 2);
        $tsHr = substr($tsMsg, 11, 2);
        $tsMin = substr($tsMsg, 14, 2);
        if ($tsMonth.$tsDay!=date('md')) {
            $strMsg .= $tsDay.' '.DateUtils::$arrShortMonths[(int)$tsMonth].' ';
        }
        $strMsg .= $tsHr.':'.$tsMin.']';
        //////////////////////////////////////////////////////

        //////////////////////////////////////////////////////
        // On ajoute l'auteur si différent du current
        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        if ($objPlayer->getField(self::FIELD_ID)!=$this->obj->getField(self::FIELD_FROM_PID)) {
            $strMsg .= ' ['.$this->obj->getSender()->getField(self::FIELD_NOM).']';
        }
        //////////////////////////////////////////////////////

        //////////////////////////////////////////////////////
        // Et on ajoute le message
        $strMsg .= ' : '.$this->obj->getField(self::FIELD_TEXTE);
        //////////////////////////////////////////////////////

        $attributes = ['data-refreshed' => $this->obj->getField(self::FIELD_TIMESTAMP)];
        return HtmlUtils::getDiv($strMsg, $attributes);
    }

}
