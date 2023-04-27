<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * CopsEventDateBean
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.04.30
 */
class CopsEventDateBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsEventDate = ($obj==null ? new CopsEventDate() : $obj);
        $this->objCopsEvent     = $this->objCopsEventDate->getCopsEvent();
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


  /**
   * @since 1.22.06.13
   * @version 1.22.06.13
   */
  public function getEventDateDisplay($tsDisplay)
  {
    if ($this->CopsEvent->isAllDayEvent()) {
      if ($this->CopsEvent->isSeveralDays()) {
        if ($this->CopsEvent->isFirstDay($tsDisplay) || date('N', $tsDisplay)==1) {
          return $this->getEventCartoucheDisplay($tsDisplay);
        }
      } else {
        return $this->getEventCartoucheDisplay($tsDisplay);
      }
    } else {
      return $this->getEventDotDisplay();
    }
  }

  public function getEventCartoucheDisplay($tsDisplay)
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-calendar-cartouche.php';
    $attributes = [
        $this->getFcDayClass($tsDisplay),
        $this->CopsEvent->getRgbCategorie(),
        $this->CopsEvent->getField('eventLibelle'),
        $this->CopsEvent->getColspan(date('N', $tsDisplay)==1 ? $tsDisplay : null)
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  public function getEventDotDisplay()
  {
    $tsDisplay = null;
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-calendar-dot.php';
    $attributes = [
        $this->getFcDayClass($tsDisplay),
        $this->CopsEvent->getRgbCategorie(),
        $this->CopsEventDate->getStrDotTime('ga', 'tstart'),
        $this->CopsEvent->getField('eventLibelle')
    ];
    return $this->getRender($urlTemplate, $attributes);
  }
  

}
