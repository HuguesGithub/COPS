<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarMonthPageBean
 * @author Hugues
 * @since 1.22.06.09
 * @version 1.22.09.21
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
     * @version 1.22.09.21
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
        list($fN, $fd, $fm, $fY) = explode(' ', date('N d m Y', mktime(1, 0, 0, $m, 1, $Y)));
        // Si le premier jour n'est pas un lundi, on recherche le lundi de sa semaine pour débuter l'affichage.
        if ($fN!=1) {
          list($fN, $fd, $fm, $fY) = explode(' ', date('N d m Y', mktime(1, 0, 0, $m, 2-$fN, $Y)));
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
    
            while (!empty($CopsEventDates)) {
              $CopsEventDate = array_shift($CopsEventDates);
              if ($CopsEventDate->getField('dStart')==date('Y-m-d', $tsDisplay)) {
                $strContent .= $CopsEventDate->getBean()->getEventDateDisplay($tsDisplay);
              }
            }
    
            $strContent .= '    </div>';
            $strContent .= '    <div class="fc-daygrid-day-bg"></div>';
            $strContent .= '  </div>';
            $strContent .= '</td>';
          }
          $strContent .= '</tr>';
        }
    
        // On récupère le rang du jour dans la semaine
        // N : 1 (pour Lundi) à 7 (pour Dimanche)
        $N = date('N', mktime(1, 0, 0, $m, $d, $Y));
        // On s'appuie dessus pour définir le premier jour de la semaine
        list($fd, $fY) = explode(' ', date('d Y', mktime(1, 0, 0, $m, $d+1-$N, $Y)));
    
        // La sixième ligne peut contenir 7 jours du mois suivant.
    
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-month.php';
        $attributes = array(
          // Les lignes à afficher
          $strContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }

}
