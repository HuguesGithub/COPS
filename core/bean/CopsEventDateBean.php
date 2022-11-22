<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEventDateBean
 * @author Hugues
 * @since 1.22.06.13
 * @version 1.22.06.13
 */
class CopsEventDateBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsEventDate = ($Obj==null ? new CopsEventDate() : $Obj);
    $this->CopsEvent     = $this->CopsEventDate->getCopsEvent();
  }

  /**
   * @since 1.22.06.13
   * @version 1.22.06.13
   */
  public function getEventDateDisplay($tsDisplay)
  {
    if ($this->CopsEvent->isAllDayEvent()) {
      if ($this->CopsEvent->isSeveralDays()) {
        if ($this->CopsEvent->isFirstDay($tsDisplay)) {
          return $this->getEventCartoucheDisplay($tsDisplay);
        } elseif (date('N', $tsDisplay)==1) {
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
    $attributes = array(
      $this->getFcDayClass($tsDisplay),
      $this->CopsEvent->getRgbCategorie(),
      $this->CopsEvent->getField('eventLibelle'),
      $this->CopsEvent->getColspan(date('N', $tsDisplay)==1 ? $tsDisplay : null),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  public function getEventDotDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-calendar-dot.php';
    $attributes = array(
      $this->getFcDayClass($tsDisplay),
      $this->CopsEvent->getRgbCategorie(),
      $this->CopsEventDate->getStrDotTime('ga', 'tstart'),
      $this->CopsEvent->getField('eventLibelle'),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getFcDayClass($tsDisplay)
  {
    // On récupère le jour courant
    $str_copsDate = get_option(self::CST_CAL_COPSDATE);
    $td = substr($str_copsDate, 9, 2);
    $tm = substr($str_copsDate, 12, 2);
    $tY = substr($str_copsDate, 15, 4);
    $tsToday   = mktime(1, 0, 0, $tm, $td, $tY);

    $strClass = '';
    ///////////////////////////////////////////////////
    // On construit la classe de la cellule comme début d'event
    if ($this->CopsEvent->isFirstDay($tsDisplay)) {
      $strClass .= 'fc-event-start ';
    }
    // On construit la classe de la cellule comme fin d'event
    if ($this->CopsEvent->isLastDay($tsDisplay)) {
      $strClass .= 'fc-event-end ';
    } elseif (!$this->CopsEvent->isSeveralWeeks()) {
      $strClass .= 'fc-event-end ';
    } elseif (!$this->CopsEvent->isOverThisWeek($tsDisplay)) {
      $strClass .= 'fc-event-end ';
    }
    // La date passée, présente ou future
    // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
    if ($tsDisplay==$tsToday) {
      $strClass .= 'fc-event-today';
    } elseif ($tsDisplay<=$tsToday) {
      $strClass .= 'fc-event-past';
    } else {
      $strClass .= 'fc-event-future';
    }
    ///////////////////////////////////////////////////
    return $strClass;
  }
  
    /**
     * @since 1.22.11.22
     * @version 1.22.11.22
     */
	public function getAllDayEvent()
	{
        $urlTemplate = 'web/pages/public/fragments/public-fragments-div-calendar-day-allday-event.php';
        $attributes = array(
            // Titre
            $this->CopsEvent->getField('eventLibelle'),
            // getRgbCategorie()
            $this->CopsEvent->getRgbCategorie(),
			// Si plusieurs colonnes
			($this->CopsEvent->isSeveralDays() ? 'fc-daygrid-event-harness-abs' : ''),
        );
        return $this->getRender($urlTemplate, $attributes);
	}
}
