<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * CopsEventDateAlldayBean
 * @author Hugues
 * @since 1.22.11.25
 * @version v1.23.05.07
 */
class CopsEventDateAlldayBean extends UtilitiesBean
{
    public function __construct($obj)
    {
        $this->objEventDate = $obj;
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getCartouche(string $tag, string $displayDate, int $nbEvents=0): string
    {
        ///////////////////////////////////////////////////
        // On récupère le jour courant
        $this->curDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        ///////////////////////////////////////////////////

        ///////////////////////////////////////////////////
        //
        

  //      $fcDayClass = $this->getFcDayClass($tag, $displayDate);

        /*
        $divClass = '';
        $divStyle = 'margin-top: 0px; ';
        $divAttributes = '';
        
        
        switch ($tag) {
            case self::CST_CAL_MONTH :
            case self::CST_CAL_WEEK :
                $this->getWeekClass($fcDayClass, $tsDisplay);
                $divClass = 'fc-daygrid-event-harness-abs';
                $divStyle .= 'top: '.(25*$nbEvents).'px; ';
                $this->initWeekData($tsDisplay, $divAttributes);
                break;
            case self::CST_CAL_DAY :
            default :
                $this->getDayClass($fcDayClass, $tsDisplay);
                break;
        }
        
        $urlTemplate = self::WEB_PPFD_ALLDAY_EVENT;
        $attributes = [
            // Titre
            $this->objCopsEvent->getField(self::FIELD_EVENT_LIBELLE),
            // getRgbCategorie()
            $this->objCopsEvent->getRgbCategorie(),
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
        */
        return 'wip';
    }
    
    /**
     * @since v1.22.11.27
     * @version v1.22.11.27
     */
    public function initWeekData($tsDisplay, &$divAttributes)
    {
        ///////////////////////////////////////////////////
        // Initialisation des variables spécifiques à l'affichage d'une semaine.
        // On doit faire un colspan si l'événement dure plusieurs jours.
        if (!$this->objCopsEvent->isSeveralWeeks()) {
            // Si l'évenément ne dure pas plus d'une semaine, c'est basique, on prend le nombre de jours.
            $nbDays = $this->objCopsEvent->getNbDays();
        } elseif ($this->objCopsEvent->isFirstWeek($tsDisplay)) {
            // S'il dure plusieurs semaines et qu'on est dans la première semaine,
            // On doit juste aller du jour jusqu'à la fin de la semaine.
            $nbDays = 8-date('N', $tsDisplay);
        } elseif ($this->objCopsEvent->isLastWeek($tsDisplay)) {
            // S'il dure plusieurs semaines et qu'on dans la dernière semaine,
            // On doit faire durer du lundi au dernier jour de l'événement.
            $nbDays = $this->objCopsEvent->getNbDaysTillEnd($tsDisplay);
        } else {
            // S'il dure plusieurs semaines et qu'on est dans une semaine intermédiaire,
            // C'est juste toute la semaine.
            $nbDays = 7;
        }
        // Si le nombre de jours est supérieur à 1, on va devoir faire un colspan
        if ($nbDays>1) {
            $divAttributes = ' data-colspan="'.($nbDays-1).'"';
        }
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05..7
     */
    public function getDayClass(string $displayDate): string
    {
        $strExtra = '';

        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objCopsEvent->isFirstDay($displayDate)) {
            $strExtra .= 'fc-event-start ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objCopsEvent->isLastDay($displayDate)) {
            $strExtra .= 'fc-event-end ';
        }

        return $strExtra;
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getWeekClass(string $displayDate): string
    {
        $strExtra = '';
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objCopsEvent->isFirstWeek($displayDate)) {
            $strExtra .= 'fc-event-start ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objCopsEvent->isLastWeek($displayDate)) {
            $strExtra .= 'fc-event-end ';
        }
        return $strExtra;
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
   public function getFcDayClass(string $tag, string $displayDate): string
    {
        $fcDayClass = 'fc-event-';

        ///////////////////////////////////////////////////
        // La date passée, présente ou future
        // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
        if ($displayDate==$this->curDate) {
            $fcDayClass .= 'today ';
        } elseif ($displayDate<$this->curDate) {
            $fcDayClass .= 'past ';
        } else {
            $fcDayClass .= 'future ';
        }

        if ($tag==self::CST_CAL_DAY) {
//            $fcDayClass .= $this->getDayClass($displayDate);
        } else {
//            $fcDayClass .= $this->getWeekClass($displayDate);
        }

        return $fcDayClass;
    }

}
