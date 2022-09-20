<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarWeekPageBean
 * @author Hugues
 * @since 1.22.06.09
 * @version 1.22.06.09
 */
class AdminCopsCalendarWeekPageBean extends AdminCopsCalendarPageBean
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

    $prevCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d-7, $y));
    $nextCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d+7, $y));
    // On récupère le rang du jour dans la semaine
    // N : 1 (pour Lundi) à 7 (pour Dimanche)
    $N = date('N', mktime(1, 0, 0, $m, $d, $y));
    // On s'appuie dessus pour définir le premier et le dernier jour de la semaine
    list($fd, $fM, $fm, $fY) = explode(' ', date('d M m Y', mktime(1, 0, 0, $m, $d+1-$N, $y)));
    list($ld, $lM, $lm, $lY) = explode(' ', date('d M m Y', mktime(1, 0, 0, $m, $d+7-$N, $y)));
    // Si $fM et $lM sont identiques, la semaine complète est dans un même mois.
    if ($fM==$lM) {
      $calendarHeader = $fd.'-'.$ld.' '.$this->arrFullMonths[$fm*1].' '.$fY; // 3-9 Juin 2030
    } else {
      // Si $fY et $lY diffèrent, la semaine est à cheval sur deux années.
      if ($fY!=$lY) {
        // 26 Dec 2021 – 1 Jan 2022
        $calendarHeader = $fd.' '.$this->arrFullMonths[$fm*1].' '.$fY.' - '.$ld.' '.$this->arrFullMonths[$lm*1].' '.$lY;
      } else {
        // Sinon, seuls les deux mois diffèrent, la semaine est à cheval sur deux mois.
        // Mar 27 – Apr 2, 2022
        $calendarHeader = $fd.' '.$this->arrFullMonths[$fm*1].' - '.$ld.' '.$this->arrFullMonths[$lm*1].' '.$lY;
      }
    }

    return parent::getCalendarContent($prevCurday, $nextCurday, $calendarHeader);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getCalendarViewContent()
  {
    // On va afficher les journées de la semaine
    // On affiche du lundi au dimanche

    // On récupère le jour courant
    list($m, $d, $Y) = explode('-', $this->curdayFormat);
    // On récupère le rang du jour dans la semaine
    // N : 1 (pour Lundi) à 7 (pour Dimanche)
    $N = date('N', mktime(1, 0, 0, $m, $d, $Y));

    // On défini le premier jour de la semaine
    if ($N!=1) {
      list($fd, $fm, $fY) = explode(' ', date('d m Y', mktime(1, 0, 0, $m, $d+1-$N, $Y)));
    } else {
      $fd = $d;
      $fm = $m;
      $fY = $Y;
    }

    // On parcourt la semaine
    $strRowHeaders = '';
    $strRowAllDay  = '';
    $strRowHoraire = '';
    for ($j=0; $j<7; $j++) {
      $tsDisplay = mktime(1, 0, 0, $fm, $fd+$j, $fY);
      $strClass = $this->getFcDayClass($tsDisplay);

      $strRowHeaders .= '<th role="columnheader" class="fc-col-header-cell fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
      $label = date('d ', $tsDisplay).$this->arrFullMonths[date('m', $tsDisplay)*1].date(' Y', $tsDisplay);
      $strRowHeaders .= '  <div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion " aria-label="'.$label.'">'.$this->arrShortDays[date('w', $tsDisplay)].date(' d/m', $tsDisplay).'</a></div>';
      $strRowHeaders .= '</th>';

      $strRowAllDay  .= '<td role="gridcell" class="fc-daygrid-day fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
      $strRowAllDay  .= '  <div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner">';
      $strRowAllDay  .= '    <div class="fc-daygrid-day-events">';
      /*
                            <div class="fc-daygrid-event-harness" style="margin-top: 0px;">
                              <a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-past" style="border-color: rgb(245, 105, 84); background-color: rgb(245, 105, 84);">
                                <div class="fc-event-main">
                                  <div class="fc-event-main-frame">
                                    <div class="fc-event-title-container">
                                      <div class="fc-event-title fc-sticky">All Day Event</div>
                                    </div>
                                  </div>
                                </div>
                                <div class="fc-event-resizer fc-event-resizer-end"></div>
                              </a>
                            </div>
       */
      $strRowAllDay  .= '      <div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div>';
      $strRowAllDay  .= '    </div>';
      $strRowAllDay  .= '    <div class="fc-daygrid-day-bg"></div>';
      $strRowAllDay  .= '  </div>';
      $strRowAllDay  .= '</td>';

      $strRowHoraire .= '<td role="gridcell" class="fc-timegrid-col fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
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

    }

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

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-week.php';
    $attributes = array(
      // La première ligne du tableau avec les dates
      $strRowHeaders,
      // La deuxième ligne du tableau avec les events "all-day"
      $strRowAllDay,
      // La première colonne contenant les horaires
      $strColumnHoraire,
      // La deuxième colonne contenant les events
      $strRowHoraire,

      //
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
