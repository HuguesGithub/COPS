<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * CopsEventDateAlldayBean
 * @author Hugues
 * @since 1.22.11.25
 * @version v1.23.04.30
 */
class CopsEventDateAlldayBean extends CopsEventDateBean
{
    public function __construct($obj=null)
    {
        parent::__construct($obj);
    }

    /**
     * @param string $tag vaut day, week ou month, selon le type de vue.
     * @param int $tsDisplay la date du jour affiché.
     * @since 1.22.11.25
     * @version 1.22.11.27
     */
    public function getCartouche($tag, $tsDisplay, $nbEvents=0)
    {
        $divClass = '';
        $divStyle = 'margin-top: 0px; ';
        $divAttributes = '';
        $fcDayClass = 'fc-event-';
        ///////////////////////////////////////////////////
        // On récupère le jour courant
        $tsToday = DateUtils::getCopsDate(self::FORMAT_TS_START_DAY);
        
        ///////////////////////////////////////////////////
        // La date passée, présente ou future
        // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
        if ($tsDisplay==$tsToday) {
            $fcDayClass .= 'today ';
        } elseif ($tsDisplay<$tsToday) {
            $fcDayClass .= 'past ';
        } else {
            $fcDayClass .= 'future ';
        }
        
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
     * @since 1.22.11.25
     * @version 1.22.11.25
     */
    public function getDayClass(&$fcDayClass, $tsDisplay)
    {
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objCopsEvent->isFirstDay($tsDisplay)) {
            $fcDayClass .= 'fc-event-start ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objCopsEvent->isLastDay($tsDisplay)) {
            $fcDayClass .= 'fc-event-end ';
        }
    }

    /**
     * @since 1.22.11.25
     * @version 1.22.11.25
     */
    public function getWeekClass(&$fcDayClass, $tsDisplay)
    {
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objCopsEvent->isFirstWeek($tsDisplay)) {
            $fcDayClass .= 'fc-event-start ';
        }
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objCopsEvent->isLastWeek($tsDisplay)) {
            $fcDayClass .= 'fc-event-end ';
        }
    }
}
