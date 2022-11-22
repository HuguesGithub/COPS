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
        // Définition des services
        $this->objCopsEventServices = new CopsEventServices();
    
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
        
        $this->prevCurday = date('m-d-Y', mktime(0, 0, 0, $m, $d-1, $y));
        $this->nextCurday = date('m-d-Y', mktime(0, 0, 0, $m, $d+1, $y));
        
        $urlTemplate = self::PF_SECTION_CAL_DAY;
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
        $calendarHeader = $d.' '.$this->arrFullMonths[$m*1].' '.$y;
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);
        
        $urlTemplate = self::PF_SECTION_ONGLET;
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
        
        $allDayEvents = $this->getAllDayEvents($tsDisplay);
        
        $divIn  = $this->getDiv($allDayEvents.$divBottom, array(self::ATTR_CLASS=>'fc-daygrid-day-events'));
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
     * @since v1.22.11.22
     * @version v1.22.11.22
     */
    public function getAllDayEvents($tsDisplay)
    {
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID => '%',
            self::FIELD_DSTART => date('Y-m-d', $tsDisplay),
            self::FIELD_DEND => date('Y-m-d', $tsDisplay),
        );
        $objsCopsEventDate = $this->objCopsEventServices->getCopsEventDates($attributes);
        
        $strContent = '';
        while (!empty($objsCopsEventDate)) {
            $objCopsEventDate = array_shift($objsCopsEventDate);
            if ($objCopsEventDate->getCopsEvent()->getField(self::FIELD_ALL_DAY_EVENT)==0) {
                continue;
            }
            $strContent .= $objCopsEventDate->getBean()->getAllDayEvent();
        }
        return $strContent;
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
