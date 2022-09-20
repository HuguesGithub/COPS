<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarDayPageBean
 * @author Hugues
 * @since 1.22.06.09
 * @version 1.22.06.09
 */
class AdminCopsCalendarDayPageBean extends AdminCopsCalendarPageBean
{
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getOngletContent()
  {
    $d = substr($this->curdayFormat, 3, 2);
    $m = substr($this->curdayFormat, 0, 2);
    $y = substr($this->curdayFormat, 6, 4);

    $prevCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d-1, $y));
    $nextCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d+1, $y));
    $calendarHeader = $d.' '.$this->arrFullMonths[$m*1].' '.$y;

    return parent::getCalendarContent($prevCurday, $nextCurday, $calendarHeader);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getCalendarViewContent()
  {
    // On récupère le jour affiché
    list($m, $d, $Y) = explode('-', $this->curdayFormat);
    $tsDisplay = mktime(1, 0, 0, $m, $d, $Y);
    $strClass = $this->getFcDayClass($tsDisplay);

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-day.php';
    $attributes = array(
      // Le Header de la journée
      $this->getRowHeaders($strClass, $tsDisplay),
      // Les events all-day de la journée
      $this->getRowAllDay($strClass, $tsDisplay),
      // La première colonne contenant les horaires
      $this->getColumnHoraire(),
      // La deuxième colonne avec les events de la journée
      $this->getViewContent($strClass, $tsDisplay),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getRowHeaders($strClass, $tsDisplay)
  {
    $strRowHeaders  = '<th role="columnheader" class="fc-col-header-cell fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
    $strRowHeaders .= ' <div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">'.$this->arrFullDays[date('w', $tsDisplay)].'</a></div>';
    $strRowHeaders .= '</th>';
    return $strRowHeaders;
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getRowAllDay($strClass, $tsDisplay)
  {
    $strRowAllDay   = '<td role="gridcell" class="fc-daygrid-day fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
    $strRowAllDay  .= ' <div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner">';
    $strRowAllDay  .= '   <div class="fc-daygrid-day-events">';
    $strRowAllDay  .= '     <div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div>';
    $strRowAllDay  .= '   </div>';
    $strRowAllDay  .= '   <div class="fc-daygrid-day-bg"></div>';
    $strRowAllDay  .= ' </div>';
    $strRowAllDay  .= '</td>';
    return $strRowAllDay;
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getColumnHoraire()
  {
    // On construit la colonne des horaires
    $strColumnHoraire = '';
    for ($h=0; $h<=23; $h++) {
      $H = str_pad($h, 2, '0', STR_PAD_LEFT);
      $strColumnHoraire .= '<tr>';
      $strColumnHoraire .= '  <td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="'.$H.':00:00">';
      $strColumnHoraire .= '    <div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame">';
      $strColumnHoraire .= '      <div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">'.date('ga', mktime($h, 0, 0)).'</div>';
      $strColumnHoraire .= '    </div>';
      $strColumnHoraire .= '  </td>';
      $strColumnHoraire .= '  <td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="'.$H.':00:00"></td>';
      $strColumnHoraire .= '</tr>';
      $strColumnHoraire .= '<tr>';
      $strColumnHoraire .= '  <td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="'.$H.':30:00"></td>';
      $strColumnHoraire .= '  <td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="'.$H.':30:00"></td>';
      $strColumnHoraire .= '</tr>';
    }
    return $strColumnHoraire;
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getViewContent($strClass, $tsDisplay)
  {
    $strRowHoraire  = '<td role="gridcell" class="fc-timegrid-col fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
    $strRowHoraire .= '  <div class="fc-timegrid-col-frame">';
    $strRowHoraire .= '    <div class="fc-timegrid-col-bg"></div>';
    $strRowHoraire .= '    <div class="fc-timegrid-col-events">';
      /*
<div class="fc-timegrid-event-harness fc-timegrid-event-harness-inset" style="inset: 0px 0% -1199px; z-index: 1;">
  <a class="fc-timegrid-event fc-v-event fc-event fc-event-draggable fc-event-resizable fc-event-end fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);">
    <div class="fc-event-main">
      <div class="fc-event-main-frame">
        <div class="fc-event-time">12:00</div>
        <div class="fc-event-title-container">
          <div class="fc-event-title fc-sticky">Long Event</div>
        </div>
      </div>
    </div>
    <div class="fc-event-resizer fc-event-resizer-end"></div>
  </a>
</div>
       */
    $strRowHoraire .= '    </div>';
    $strRowHoraire .= '    <div class="fc-timegrid-col-events"></div>';
    $strRowHoraire .= '    <div class="fc-timegrid-now-indicator-container"></div>';
    $strRowHoraire .= '  </div>';
    $strRowHoraire .= '</td>';
    return $strRowHoraire;
  }

}
