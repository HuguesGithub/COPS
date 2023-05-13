<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * CopsEventDateLongBean
 * @author Hugues
 * @since v1.23.05.11
 * @version v1.23.05.14
 */
class CopsEventDateLongBean extends CopsEventDateBean
{
    public function __construct($obj=null)
    {
        parent::__construct($obj);
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.14
     */
    public function getCartouche(string $tag, string $displayDate, int $nbEvents=0): string
    {
        ///////////////////////////////////////////////////
        // On récupère le jour courant
        $this->curDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        ///////////////////////////////////////////////////

        $this->objEvent = $this->objEventDate->getCopsEvent();
        ///////////////////////////////////////////////////
        //
        $divAttributes = 'title="'.$this->objEvent->getField(self::FIELD_EVENT_LIBELLE).'"';
        $divClass = '';
        $divStyle = 'margin-top: 0px; ';
        $fcDayClass = '';

        if ($tag==self::CST_CAL_DAY) {
            $fcDayClass .= $this->getExtraClassForDay($displayDate);
        } elseif ($tag==self::CST_CAL_MONTH || $tag==self::CST_CAL_WEEK) {
            $nbDays = 0;
            $divClass = self::CST_FC_DG_EVENT_HAR_ABS;
            if ($displayDate==$this->objEventDate->getField(self::FIELD_DSTART)) {
                $left = round(100*$this->objEventDate->getField(self::FIELD_TSTART)/1440);
            } else {
                $left = 0;
            }
            $right = -1*round(100*$this->objEventDate->getField(self::FIELD_TEND)/1440);
            if ($displayDate==$this->objEventDate->getField(self::FIELD_DEND)) {
                $right += 100;
            }
            $divStyle .= 'top: '.(25*$nbEvents).'px; left: '.$left.'%; right: '.$right.'%; ';

            $fcDayClass .= $this->getExtraClassForWeekMonth($displayDate, $nbDays);

            // Si le nombre de jours est supérieur à 1, on va devoir ajouter au style
            // un right qui colle bien, pour finir le div proportionnellement.

        } else {
            // TODO : valeur inattendue
        }

        $urlTemplate = self::WEB_PPFD_ALLDAY_EVENT;
        $attributes = [
            // Titre
            $this->objEvent->getField(self::FIELD_EVENT_LIBELLE),
            // getRgbCategorie()
            $this->objEvent->getRgbCategorie(),
            // Si plusieurs colonnes, pas pertinent sur la vue Daily
            $divClass,
            // Les styles éventuels
            $divStyle,
            // D'autres classes supplémentaires
            $fcDayClass,
            // D'autres attributs.
            $divAttributes,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getExtraClassForDay(string $displayDate): string
    {
        $strExtra = '';
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objEvent->isFirstWeek($displayDate)) {
            $strExtra .= self::CST_FC_EVENT_START.' ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objEvent->isLastWeek($displayDate)) {
            $strExtra .= self::CST_FC_EVENT_END.' ';
        }
        return $strExtra;
    }

    /**
     * @since v1.23.05.10
     * @version v1.23.05.14
     */
    public function getExtraClassForWeekMonth(string $displayDate, int &$nbDays): string
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
        // Initialisation des variables spécifiques à l'affichage d'une semaine.
        // On doit faire un colspan si l'événement dure plusieurs jours.
        if (!$this->objEvent->isSeveralWeeks()) {
            // Si l'évenément ne dure pas plus d'une semaine, c'est basique, on prend le nombre de jours.
            $nbDays = $this->objEvent->getNbDays();
        } elseif ($this->objEvent->isFirstWeek($displayDate)) {
            // S'il dure plusieurs semaines et qu'on est dans la première semaine,
            // On doit juste aller du jour jusqu'à la fin de la semaine.
            $nbDays = 8-DateUtils::getStrDate('N', $displayDate);
        } elseif ($this->objEvent->isLastWeek($displayDate)) {
            // S'il dure plusieurs semaines et qu'on est dans la dernière semaine,
            // On doit faire durer du lundi au dernier jour de l'événement.
            $nbDays = DateUtils::getNbDaysBetween($displayDate, $this->objEvent->getField(self::FIELD_DATE_FIN));
        } else {
            // S'il dure plusieurs semaines et qu'on est dans une semaine intermédiaire,
            // C'est juste toute la semaine.
            $nbDays = 7;
        }

        return $fcDayClass;
    }

}
