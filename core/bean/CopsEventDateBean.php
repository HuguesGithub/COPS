<?php
namespace core\bean;

use core\domain\CopsEventDateClass;
use core\utils\DateUtils;

/**
 * CopsEventDateBean
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.05.21
 */
class CopsEventDateBean extends UtilitiesBean
{
    public function __construct($obj)
    {
        $this->objEventDate = $obj;
    }

    /**
     * @since 1.22.06.09
     * @version 1.22.06.09
     */
    public function getFcDayClass($tsDisplay)
    {
        // On récupère le jour courant
        $tsToday = DateUtils::getCopsDate(self::FORMAT_TS_START_DAY);

        $strClass = 'fc-event-';
        // La date passée, présente ou future
        // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
        if ($tsDisplay==$tsToday) {
            $strClass .= 'today ';
        } elseif ($tsDisplay<$tsToday) {
            $strClass .= 'past ';
        } else {
            $strClass .= 'future ';
        }
        
        ///////////////////////////////////////////////////
        // Si c'est le début de l'event
        if ($this->objCopsEvent->isFirstDay($tsDisplay)) {
            $strClass .= 'fc-event-start ';
        }
        
        ///////////////////////////////////////////////////
        // Si c'est la fin de l'event
        if ($this->objCopsEvent->isLastWeek($tsDisplay)) {
            $strClass .= 'fc-event-end ';
        }

        return $strClass;
    }

}
