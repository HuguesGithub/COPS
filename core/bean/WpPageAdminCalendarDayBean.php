<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarDayBean
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.21
 */
class WpPageAdminCalendarDayBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_DAY;
        $this->titreSubOnglet = 'Quotidien';
    
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
        // On récupère le jour courant
        list($m, $d, $y) = explode('-', $this->curStrDate);
		$tsDisplay = mktime(0, 0, 0, $m, $d, $y);
		$strClass = $this->getFcDayClass($tsDisplay);
		
        // On construit la colonne des horaires
        $strColumnHoraire = '';
        for ($h=0; $h<=23; $h++) {
            $strColumnHoraire .= $this->getColumnHoraire($h);
        }
        /////////////////////////////////////////
		
		$urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-day.php';
		$attributes = array(
		    // Le Header de la journée
		    $this->getRowHeaders($strClass, $tsDisplay),
		    // Les events all-day de la journée
		    $this->getRowAllDay($strClass, $tsDisplay),
		    // La première colonne contenant les horaires
		    $strColumnHoraire,
		    // La deuxième colonne avec les events de la journée
		    $this->getRowHoraire($strClass, $tsDisplay),
		);
		$viewContent = $this->getRender($urlTemplate, $attributes);
		
		$prevCurday = date('m-d-Y', mktime(0, 0, 0, $m, $d-1, $y));
		$nextCurday = date('m-d-Y', mktime(0, 0, 0, $m, $d+1, $y));

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
        
		$calendarHeader = $d.' '.$this->arrFullMonths[$m*1].' '.$y;
		
        /////////////////////////////////////////
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
            'section-cal-day',
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
     * @since 1.22.11.21
     * @version 1.22.11.21
     */
    public function getRowHeaders($strClass, $tsDisplay)
    {
		$url = '#';
		$aClass = 'fc-col-header-cell-cushion text-white';
		$divContent = $this->getLink($this->arrFullDays[date('w', $tsDisplay)], $url, $aClass);
		$thContent = $this->getDiv($divContent, array(self::ATTR_CLASS=>'fc-scrollgrid-sync-inner'));
        $attributes = array(
            'role' => 'columnheader',
            self::ATTR_CLASS => 'fc-col-header-cell fc-day '.$strClass,
            self::ATTR_DATA_DATE => date('Y-m-d', $tsDisplay),
        );
        return $this->getBalise(self::TAG_TH, $thContent, $attributes);
    }

    /**
     * @since 1.22.11.21
     * @version 1.22.11.21
     */
    public function getRowAllDay($strClass, $tsDisplay)
    {
        $botAttributes = array(
            self::ATTR_CLASS => 'fc-daygrid-day-bottom',
            self::ATTR_STYLE => 'margin-top: 0px;',
        );
        $divBottom = $this->getDiv('', $botAttributes);
		
		$divIn  = $this->getDiv($divBottom, array(self::ATTR_CLASS=>'fc-daygrid-day-events'));
		$divIn .= $this->getDiv('', array(self::ATTR_CLASS=>'fc-daygrid-day-bg'));
		
		$tdContent = $this->getDiv($divIn, array(self::ATTR_CLASS=>'fc-daygrid-day-frame fc-scrollgrid-sync-inner'));
        $attributes = array(
            'role'        => 'gridcell',
            self::ATTR_CLASS     => 'fc-daygrid-day fc-day ' . $strClass,
            self::ATTR_DATA_DATE => date('Y-m-d', $tsDisplay),
        );
        return $this->getBalise(self::TAG_TD, $tdContent, $attributes);
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
        $divContent .= $this->getDiv($this->getDayCell(), array(self::ATTR_CLASS=>'fc-timegrid-col-events'));
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
	public function getDayCell()
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
}
