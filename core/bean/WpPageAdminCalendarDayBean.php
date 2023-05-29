<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminCalendarDayBean
 * @author Hugues
 * @since 1.22.11.21
 * @version v1.23.05.28
 */
class WpPageAdminCalendarDayBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_DAY;
        $this->titreSubOnglet = self::LABEL_DAILY;
        /////////////////////////////////////////
        // Définition des services
        $this->objCopsEventServices = new CopsEventServices();

        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * @since 1.22.11.21
     * @version v1.23.05.28
     */
    public function getOngletContent()
    {
        $this->objsAlldayEventDate = [];
        $this->objsTodayEventDate = [];
        
        /////////////////////////////////////////
        // On récupère le jour courant
        [$m, $d, $y] = explode('-', (string) $this->curStrDate);
        $tsDisplay = mktime(0, 0, 0, $m, $d, $y);
        $strClass = $this->getFcDayClass($tsDisplay);
        
        /////////////////////////////////////////
        // On récupère tous les events
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => self::SQL_JOKER_SEARCH,
                self::FIELD_DSTART => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $tsDisplay),
                self::FIELD_DEND => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $tsDisplay),
            ],
            self::SQL_ORDER_BY => [self::FIELD_DSTART, self::FIELD_DEND],
            self::SQL_ORDER => [self::SQL_ORDER_ASC, self::SQL_ORDER_DESC]];
        $objsCopsEventDate = $this->objCopsEventServices->getEventDates($attributes);
        // On va trier les event "Allday" de ceux qui ne le sont pas.
        while (!empty($objsCopsEventDate)) {
            $objCopsEventDate = array_shift($objsCopsEventDate);
            if ($objCopsEventDate->getEvent()->isAllDayEvent()) {
                $this->objsAlldayEventDate[] = $objCopsEventDate;
            } else {
                $this->objsTodayEventDate[] = $objCopsEventDate;
            }
        }
        /////////////////////////////////////////
        
        // On construit la colonne des horaires
        $strColumnHoraire = '';
        for ($h=0; $h<=23; ++$h) {
            $strColumnHoraire .= $this->getColumnHoraire($h);
        }
        /////////////////////////////////////////
        
        $this->prevCurday = DateUtils::getStrDate(self::FORMAT_DATE_MDY, mktime(0, 0, 0, $m, $d-1, $y));
        $this->nextCurday = DateUtils::getStrDate(self::FORMAT_DATE_MDY, mktime(0, 0, 0, $m, $d+1, $y));
        
        $urlTemplate = self::PF_SECTION_CAL_DAY;
        $attributes = [
            // Le Header de la journée
            $this->getRowHeaders($strClass, $tsDisplay),
            // Les events all-day de la journée
            $this->getRowAllDay($strClass, $tsDisplay),
            // La première colonne contenant les horaires
            $strColumnHoraire,
            // La deuxième colonne avec les events de la journée
            $this->getRowHoraire($strClass, $tsDisplay),
        ];
        $viewContent = $this->getRender($urlTemplate, $attributes);
        $calendarHeader = $d.' '.DateUtils::arrFullMonths[$m*1].' '.$y;
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);
        
        $urlTemplate = self::PF_SECTION_ONGLET;
        $attributes = [
            // L'id de la page
            'section-cal-day',
            // Le bouton éventuel de création / retour...
            '',
            //$strButtonRetour,
            // Le nom du bloc du menu de gauche
            $this->titreOnglet,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.11.21
     * @version v1.23.05.28
     */
    public function getRowHeaders(string $strClass, int $tsDisplay): string
    {
        $url = '#';
        $aClass = self::CST_FC_COL_HEADER_CELL_CSH.' '.self::CST_TEXT_WHITE;
        $divContent = HtmlUtils::getLink(DateUtils::arrFullDays[date('w', $tsDisplay)], $url, $aClass);
        $thContent = HtmlUtils::getDiv($divContent, [self::ATTR_CLASS=>self::CST_FC_SCROLLGRID_SYNC_IN]);
        $attributes = [
            self::ATTR_ROLE => self::CST_COLUMNHEADER,
            self::ATTR_CLASS => self::CST_FC_COL_HEADER_CELL.' '.self::CST_FC_DAY.' '.$strClass,
            self::ATTR_DATA_DATE => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $tsDisplay)
        ];
        return HtmlUtils::getTh($thContent, $attributes);
    }

    /**
     * @since 1.22.11.21
     * @version v1.23.05.28
     */
    public function getRowAllDay(string $strClass, int $tsDisplay): string
    {
        $botAttributes = [
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_BTM,
            self::ATTR_STYLE => 'margin-top: 0px;'
        ];
        $divBottom = HtmlUtils::getDiv('', $botAttributes);
        
        $allDayEvents = $this->getAllDayEvents($tsDisplay);
        
        $divIn  = HtmlUtils::getDiv($allDayEvents.$divBottom, [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_EVENTS]);
        $divIn .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_BG]);
        
        $tdContent = HtmlUtils::getDiv($divIn, [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_FRAME.' '.self::CST_FC_SCROLLGRID_SYNC_IN]);
        $attributes = [
            self::ATTR_ROLE => self::CST_GRIDCELL,
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY.' '.self::CST_FC_DAY.' ' . $strClass,
            self::ATTR_DATA_DATE => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $tsDisplay)
        ];
        return $this->getBalise(self::TAG_TD, $tdContent, $attributes);
    }
    
    /**
     * @since v1.22.11.22
     * @version v1.22.11.25
     */
    public function getAllDayEvents($tsDisplay)
    {
        $strContent = '';
        while (!empty($this->objsAlldayEventDate)) {
            $objCopsEventDate = array_shift($this->objsAlldayEventDate);
            $strContent .= $objCopsEventDate->getBean()->getCartouche(self::CST_CAL_DAY, $tsDisplay);
        }
        return $strContent;
    }

    /**
     * @since v1.22.11.21
     * @version v1.23.05.28
     */
    public function getRowHoraire(string $strClass, int $tsDisplay): string
    {
        $divContent  = HtmlUtils::getDiv('', [self::ATTR_CLASS=>'fc-timegrid-col-bg']);
        $divContent .= HtmlUtils::getDiv($this->getDayCell(), [self::ATTR_CLASS=>'fc-timegrid-col-events']);
        $divContent .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>'fc-timegrid-col-events']);
        $divContent .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>'fc-timegrid-now-indicator-container']);
        
        $tdContent = HtmlUtils::getDiv($divContent, [self::ATTR_CLASS=>'fc-timegrid-col-frame']);
        $tdAttributes = [
            'role' => 'gridcell',
            self::ATTR_CLASS => 'fc-timegrid-col fc-day '.$strClass,
            self::ATTR_DATA_DATE => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $tsDisplay)
        ];
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
  <a class="fc-timegrid-event fc-v-event fc-event fc-event-draggable fc-event-resizable fc-event-end fc-event-past"
   style="border-color: rgb(243, 156, 18); background-color: rgb(243, 156, 18);">
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
