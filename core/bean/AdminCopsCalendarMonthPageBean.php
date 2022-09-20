<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarMonthPageBean
 * @author Hugues
 * @since 1.22.06.09
 * @version 1.22.06.09
 */
class AdminCopsCalendarMonthPageBean extends AdminCopsCalendarPageBean
{
  public function __construct()
  {
    parent::__construct();
    $this->CopsEventServices = new CopsEventServices();
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

    $prevCurday = date('m-d-Y', mktime(1, 0, 0, $m-1, $d, $y));
    $nextCurday = date('m-d-Y', mktime(1, 0, 0, $m+1, $d, $y));
    $calendarHeader = $this->arrFullMonths[date('m', mktime(1, 0, 0, $m, $d, $y))*1].date(' Y', mktime(1, 0, 0, $m, $d, $y));// Juin 2030

    return parent::getCalendarContent($prevCurday, $nextCurday, $calendarHeader);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getCalendarViewContent()
  {
    // On va afficher les journées du mois.
    // On affiche toujours 6 lignes.
    // On affiche du lundi au dimanche
    // La première ligne contient le premier jour du mois.

    // On récupère le jour courant
    list($m, $d, $Y) = explode('-', $this->curdayFormat);

    // On défini le premier jour du mois
    list($fN, $fd, $fM, $fm, $fY) = explode(' ', date('N d M m Y', mktime(1, 0, 0, $m, 1, $Y)));
    // Si le premier jour n'est pas un lundi, on recherche le lundi de sa semaine pour débuter l'affichage.
    if ($fN!=1) {
      list($fN, $fd, $fM, $fm, $fY) = explode(' ', date('N d M m Y', mktime(1, 0, 0, $m, 2-$fN, $Y)));
    }

    // On parcourt le mois
    $strContent = '';
    for ($w=0; $w<6; $w++) {
      $strContent .= '<tr>';
      for ($j=0; $j<7; $j++) {
        $tsDisplay = mktime(1, 0, 0, $fm, $fd+($j+$w*7), $fY);
        $strClass = $this->getFcDayClass($tsDisplay);

        // Construction de la cellule
        $strContent .= '<td class="fc-daygrid-day fc-day '.$strClass.'" data-date="'.date('Y-m-d', $tsDisplay).'">';
        $strContent .= '  <div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner">';
        $strContent .= '    <div class="fc-daygrid-day-top"><a class="fc-daygrid-day-number">'.date('d', $tsDisplay).'</a></div>';
        $strContent .= '    <div class="fc-daygrid-day-events">';

        $attributes[self::SQL_WHERE_FILTERS] = array(
          self::FIELD_ID     => self::SQL_JOKER_SEARCH,
          self::FIELD_DSTART => date('Y-m-d', $tsDisplay),
          self::FIELD_DEND   => date('Y-m-d', $tsDisplay),
        );
        $CopsEventDates = $this->CopsEventServices->getCopsEventDates($attributes);

        $nb = count($CopsEventDates);
        while (!empty($CopsEventDates)) {
          $CopsEventDate = array_shift($CopsEventDates);
          if ($CopsEventDate->getField('dStart')==date('Y-m-d', $tsDisplay)) {
            $strContent .= $CopsEventDate->getBean()->getEventDateDisplay($tsDisplay);
          }
          // TODO : On ne veut pas réafficher les événements de la veille qui déborderait sur le jour présent.
        }

        // Ici se retrouvent les événements
        if (date('m', $tsDisplay)==6 && date('d', $tsDisplay)>=3 && date('d', $tsDisplay)<=9) {
          //$strContent .= '      <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future"><div class="fc-daygrid-event-dot" style="border-color: rgb(0, 166, 90);"></div><div class="fc-event-time">7a</div><div class="fc-event-title">Service : 07h-15h</div></a></div>';
        }
        if (date('d', $tsDisplay)==11) {
          //$strContent .= '      <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -83.4167px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-end fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-time">12a</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Long Event</div></div></div></div></a></div>';
        }
        /*
        if (date('d', $tsDisplay)==1) {
          $strContent .= '      <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future" style="border-color: rgb(245, 105, 84); background-color: rgb(245, 105, 84);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">All Day Event</div></div></div></div><div class="fc-event-resizer fc-event-resizer-end"></div></a></div>';
        }
        if (date('d', $tsDisplay)==10) {
          $strContent .= '      <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -83.4167px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-end fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-time">12a</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Long Event</div></div></div></div></a></div>';
        }
        if (date('d', $tsDisplay)==14) {
          $strContent .= '      <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future"><div class="fc-daygrid-event-dot" style="border-color: rgb(0, 166, 90);"></div><div class="fc-event-time">7p</div><div class="fc-event-title">Birthday Party</div></a></div>';
        }
        if (date('d', $tsDisplay)==17) {
          $strContent .= '      <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -166.833px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future" style="border-color: rgb(40, 167, 69); background-color: rgb(40, 167, 69);"><div class="fc-event-main" style="color: rgb(255, 255, 255);"><div class="fc-event-main-frame"><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Lunch</div></div></div></div><div class="fc-event-resizer fc-event-resizer-end"></div></a></div>';
        }
        if (date('d', $tsDisplay)==21) {
          $strContent .= '      <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-start fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-time">12a</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Long Event</div></div></div></div></a></div>';
        }
        */
        // TODO : Selon le nombre d'events dans la cellule, le margin devrait être variable : environ 25px par event dans la journée.
        //$strContent .= '      <div class="fc-daygrid-day-bottom" style="margin-top: '.(25*$nb).'px;"></div>';
        $strContent .= '    </div>';
        $strContent .= '    <div class="fc-daygrid-day-bg"></div>';
        $strContent .= '  </div>';
        $strContent .= '</td>';
      }
      $strContent .= '</tr>';
    }



    /*
          * // Ici se retrouvent les événements
          * // Un All day Event
          * <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future" style="border-color: rgb(245, 105, 84); background-color: rgb(245, 105, 84);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">All Day Event</div></div></div></div><div class="fc-event-resizer fc-event-resizer-end"></div></a></div>
          * // Un hourly Event
          * <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future"><div class="fc-daygrid-event-dot" style="border-color: rgb(0, 166, 90);"></div><div class="fc-event-time">7p</div><div class="fc-event-title">Birthday Party</div></a></div>
          * // Un Long Event (sur plusieurs jours)
          * // 3 jours dans la même semaine
          * <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -166.833px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-future" style="border-color: rgb(40, 167, 69); background-color: rgb(40, 167, 69);"><div class="fc-event-main" style="color: rgb(255, 255, 255);"><div class="fc-event-main-frame"><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Lunch</div></div></div></div><div class="fc-event-resizer fc-event-resizer-end"></div></a></div>
          * // 3 jours débutés la semaine précédente
          * <div class="fc-daygrid-event-harness fc-daygrid-event-harness-abs" style="top: 0px; left: 0px; right: -83.4167px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-end fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-time">12a</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Long Event</div></div></div></div></a></div>
          * // 3 jours se terminant la semaine suivante
          * <div class="fc-daygrid-event-harness" style="margin-top: 0px;"><a class="fc-daygrid-event fc-daygrid-block-event fc-h-event fc-event fc-event-draggable fc-event-start fc-event-past" style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);"><div class="fc-event-main"><div class="fc-event-main-frame"><div class="fc-event-time">12a</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">Long Event</div></div></div></div></a></div>
     */



    // On récupère le rang du jour dans la semaine
    // N : 1 (pour Lundi) à 7 (pour Dimanche)
    $N = date('N', mktime(1, 0, 0, $m, $d, $y));
    // On s'appuie dessus pour définir le premier jour de la semaine
    list($fd, $fM, $fY) = explode(' ', date('d M Y', mktime(1, 0, 0, $m, $d+1-$N, $y)));

    // La sixième ligne peut contenir 7 jours du mois suivant.

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-month.php';
    $attributes = array(
      // Les lignes à afficher
      $strContent,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
