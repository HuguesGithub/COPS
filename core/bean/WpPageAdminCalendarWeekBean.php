<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarWeekBean
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.21
 */
class WpPageAdminCalendarWeekBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_WEEK;
        $this->titreSubOnglet = 'Hebdomadaire';
    
        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }

    /**
     * @since 1.22.11.21
     * @version 1.22.11.21
     */
    public function getOngletContent()
    {
        /////////////////////////////////////////
        // On va afficher les jours de la semaine.
        // On affiche toujours 1 ligne.
        // On affiche du lundi au dimanche
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On récupère le jour courant
        list($m, $d, $y) = explode('-', $this->curStrDate);
        // On récupère le rang du jour dans la semaine
        // N : 1 (pour Lundi) à 7 (pour Dimanche)
        $fN = date('N', mktime(0, 0, 0, $m, $d, $y));
        // On s'appuie dessus pour définir le premier et le dernier jour de la semaine
        list($fd, $fM, $fm, $fY) = explode(' ', date('d M m Y', mktime(0, 0, 0, $m, $d+1-$fN, $y)));
        list($ld, $lM, $lm, $lY) = explode(' ', date('d M m Y', mktime(0, 0, 0, $m, $d+7-$fN, $y)));
        // Si $fM et $lM sont identiques, la semaine complète est dans un même mois.
        if ($fM==$lM) {
            $calendarHeader = $fd.'-'.$ld.' '.$this->arrFullMonths[$fm*1].' '.$fY; // 3-9 Juin 2030
        } elseif ($fY!=$lY) {
            // Si $fY et $lY diffèrent, la semaine est à cheval sur deux années.
            // 26 Dec 2021 – 1 Jan 2022
            $calendarHeader = $fd.' '.$this->arrFullMonths[$fm*1].' '.$fY.' - '.$ld.' '.$this->arrFullMonths[$lm*1].' '.$lY;
        } else {
            // Sinon, seuls les deux mois diffèrent, la semaine est à cheval sur deux mois.
            // Mar 27 – Apr 2, 2022
            $calendarHeader = $fd.' '.$this->arrFullMonths[$fm*1].' - '.$ld.' '.$this->arrFullMonths[$lm*1].' '.$lY;
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On parcourt la semaine
        $strRowHeaders = '';
        $strRowAllDay  = '';
        $strRowHoraire = '';
        for ($j=0; $j<7; $j++) {
            $tsDisplay = mktime(0, 0, 0, $fm, $fd+$j, $fY);
            $strClass = $this->getFcDayClass($tsDisplay);
            
            $strRowHeaders .= $this->getRowHeader($strClass, $tsDisplay);
            $strRowAllDay .= $this->getRowAllDay($strClass, $tsDisplay);
            $strRowHoraire .= $this->getRowHoraire($strClass, $tsDisplay);
        }
        
        // On construit la colonne des horaires
        $strColumnHoraire = '';
        for ($h=0; $h<=23; $h++) {
            $strColumnHoraire .= $this->getColumnHoraire($h);
        }
        /////////////////////////////////////////
        
        $prevCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d-7, $y));
        $nextCurday = date('m-d-Y', mktime(1, 0, 0, $m, $d+7, $y));
        
        $urlElements = array(
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => $this->slugSubOnglet,
        );
        $urlToday = $this->getUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $prevCurday;
        $urlPrev  = $this->getUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $nextCurday;
        $urlNext  = $this->getUrl($urlElements);
        
        $urlElements[self::CST_CAL_CURDAY] = $this->curStrDate;
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_MONTH;
        $urlMonth = $this->getUrl($urlElements);
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
        $urlWeek  = $this->getUrl($urlElements);
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
        $urlDay   = $this->getUrl($urlElements);
       
        /////////////////////////////////////////
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
        );
        $viewContent = $this->getRender($urlTemplate, $attributes);
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar.php';
        $attributes = array(
            // L'url pour accéder au mois/semaine/jour précédent
            $urlPrev,
            // L'url pour accéder au mois/semaine/jour suivant
            $urlNext,
            // L'url pour accéder au mois/semaine/jour courant
            $urlToday,
            // Le bandeau pour indiquer l'intervalle (mois/semaine/jour) visionné
            $calendarHeader,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_MONTH ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans le mois
            $urlMonth,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_WEEK ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans la semaine
            $urlWeek,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_DAY ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans le jour
            $urlDay,
            // Le contenu du calendrier à visionner
            $viewContent,
        );
        $mainContent = $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-cal-week',
            // Le bouton éventuel de création / retour...
            '',//$strButtonRetour,
            // Le nom du bloc du menu de gauche
            $this->titreOnglet,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getColumnHoraire($h)
    {
        $hPadded = str_pad($h, 2, '0', STR_PAD_LEFT);
        $cushionAttributes = array(self::ATTR_CLASS=>'fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion');
        $shrinkCushion = $this->getDiv(date('ga', mktime($h, 0, 0)), $cushionAttributes);
        $frameAttributes = array(self::ATTR_CLASS=>'fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame');
        $shrinkFrame = $this->getDiv($shrinkCushion, $frameAttributes);
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink',
            'data-time' => $hPadded.':00:00',
        );
        $firstRow = $this->getBalise(self::TAG_TD, $shrinkFrame, $tdAttributes);
        
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane',
            'data-time' => $hPadded.':00:00',
        );
        $firstRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00',
        );
        $secondRow = $this->getBalise(self::TAG_TD, '', $tdAttributes);
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00',
        );
        $secondRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        return $this->getBalise(self::TAG_TR, $firstRow).$this->getBalise(self::TAG_TR, $secondRow);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getRowHoraire($strClass, $tsDisplay)
    {
        $divContent  = $this->getDiv('', array(self::ATTR_CLASS=>'fc-timegrid-col-bg'));
        $divContent .= $this->getDiv($this->getWeekCellBis(), array(self::ATTR_CLASS=>'fc-timegrid-col-events'));
        $divContent .= $this->getDiv('', array(self::ATTR_CLASS=>'fc-timegrid-col-events'));
        $divContent .= $this->getDiv('', array(self::ATTR_CLASS=>'fc-timegrid-now-indicator-container'));
        
        $tdContent = $this->getDiv($divContent, array(self::ATTR_CLASS=>'fc-timegrid-col-frame'));
        $tdAttributes = array(
            'role' => 'gridcell',
            self::ATTR_CLASS => 'fc-timegrid-col fc-day '.$strClass,
            'data-date' => date('Y-m-d', $tsDisplay),
        );
        return $this->getBalise(self::TAG_TD, $tdContent, $tdAttributes);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getRowAllDay($strClass, $tsDisplay)
    {
        $botAttributes = array(
            self::ATTR_CLASS => 'fc-daygrid-day-bottom',
            self::ATTR_STYLE => 'margin-top: 0px;',
        );
        $divBottom = $this->getDiv('', $botAttributes);
        
        $eventContent = $this->getWeekCell().$divBottom;
        
        $divContent  = $this->getDiv($eventContent, array(self::ATTR_CLASS=>'fc-daygrid-day-events'));
        $divContent .= $this->getDiv('', array(self::ATTR_CLASS=>'fc-daygrid-day-bg'));
        $divAttributes = array(self::ATTR_CLASS=>'fc-daygrid-day-frame fc-scrollgrid-sync-inner');

        $tdContent = $this->getDiv($divContent, $divAttributes);
        $tdAttributes = array(
            'role' => 'gridcell',
            self::ATTR_CLASS => 'fc-daygrid-day fc-day '.$strClass,
            'data-date' => date('Y-m-d', $tsDisplay),
        );
        return $this->getBalise(self::TAG_TD, $tdContent, $tdAttributes);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getRowHeader($strClass, $tsDisplay)
    {
        $urlBase = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_CALENDAR.self::CST_AMP.self::CST_SUBONGLET.'=';
        $url = $urlBase.self::CST_CAL_DAY.self::CST_AMP.self::CST_CAL_CURDAY.'='.date('m-d-Y', $tsDisplay);

        $label = date('d ', $tsDisplay).$this->arrFullMonths[date('m', $tsDisplay)*1].date(' Y', $tsDisplay);
        $aContent = $this->arrShortDays[date('w', $tsDisplay)].date(' d/m', $tsDisplay);
        $aAttributes = array(
            'aria-label' => $label,
        );
        $divContent = $this->getLink($aContent, $url, 'fc-col-header-cell-cushion text-white', $aAttributes);
        $thContent = $this->getDiv($divContent, array(self::ATTR_CLASS=>'fc-scrollgrid-sync-inner'));
        $thAttributes = array(
            'role' => 'columnheader',
            self::ATTR_CLASS => 'fc-col-header-cell fc-day '.$strClass,
            'data-date' => date('Y-m-d', $tsDisplay),
        );
        return $this->getBalise(self::TAG_TH, $thContent, $thAttributes);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getWeekCellBis()
    {
        return '';
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
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getWeekCell()
    {
        return '';
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
    }

}
