<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * CopsEventDateDotBean
 * @author Hugues
 * @since v1.23.05.11
 * @version v1.23.05.28
 */
class CopsEventDateDotBean extends CopsEventDateBean
{
    /**
     * @since v1.23.05.11
     * @version v1.23.05.28
     */
    public function getCartouche(string $tag, string $displayDate): string
    {
        ///////////////////////////////////////////////////
        // On récupère le jour courant
        $this->curDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        ///////////////////////////////////////////////////

        $this->objEvent = $this->objEventDate->getEvent();
        ///////////////////////////////////////////////////
        //
        $fcDayClass = '';

        $ratio = 1190/1440;
        $strInset  = 'inset: ';

        if (!$this->objEvent->isSeveralDays()) {
            $intTop = round(2+$ratio*$this->objEventDate->getField(self::FIELD_TSTART));
            $height = $this->objEventDate->getField(self::FIELD_TEND)-$this->objEventDate->getField(self::FIELD_TSTART);
            $intHeight = round(-4+$ratio*($height));
            $intBottom = -1*$intTop-$intHeight;
        } elseif ($this->objEventDate->getField(self::FIELD_DSTART)==$displayDate) {
            $intTop = round(2+$ratio*$this->objEventDate->getField(self::FIELD_TSTART));
            $intBottom = -1*1190;
        } else {
            $intTop = 0;
            $height = $this->objEventDate->getField(self::FIELD_TEND);
            $intHeight = round(-4+$ratio*($height));
            $intBottom = -1*$intHeight;
        }

        $strInset .= $intTop.'px ';
        $strInset .= '0% ';
        $strInset .= $intBottom.'px ';
        $strInset .= '0% ';

        if ($tag==self::CST_CAL_DAY || $tag==self::CST_CAL_WEEK) {
            $fcDayClass .= $this->getExtraClassForDayWeek($displayDate);
            $urlTemplate = self::WEB_PPFD_INSET_EVENT;
            $attributes = [
                // Inset - Top, Right, Bottom, Left
                $strInset,
                // Classes
                $fcDayClass,
                // Couleur
                $this->objEvent->getRgbCategorie(),
                // Heure
                $this->objEventDate->getAmPmTime(self::FIELD_TSTART, false),
                // Libellé
                $this->objEvent->getField(self::FIELD_EVENT_LIBELLE),
            ];
        } elseif ($tag==self::CST_CAL_MONTH) {
            $fcDayClass .= $this->getExtraClassForMonth($displayDate);
            $urlTemplate = self::WEB_PPFD_DOT_EVENT;
            $attributes = [
                //
                $fcDayClass,
                // getRgbCategorie()
                $this->objEvent->getRgbCategorie(),
                // Heure
                $this->objEventDate->getAmPmTime(self::FIELD_TSTART),
                // Titre
                $this->objEvent->getField(self::FIELD_EVENT_LIBELLE),
            ];
        } else {
            // TODO : valeur inattendue
        }

        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getExtraClassForDayWeek(string $displayDate): string
    {
        return '';
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getExtraClassForMonth(string $displayDate): string
    {
        $fcDayClass = '';
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objEvent->isFirstWeek($displayDate)) {
            $fcDayClass .= self::CST_FC_EVENT_START.' ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objEvent->isLastWeek($displayDate)) {
            $fcDayClass .= self::CST_FC_EVENT_END.' ';
        }
        ///////////////////////////////////////////////////
        // Si c'est aujourd'hui
        if ($this->objEvent->isToday($displayDate)) {
            $fcDayClass .= self::CST_FC_EVENT_TODAY.' ';
        }
        return $fcDayClass;
    }
}
