<?php
namespace core\bean;

use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\UrlUtils;

/**
 * Classe AdminPageCalendarDayBean
 * @author Hugues
 * @since 1.22.11.21
 * @version v1.23.05.07
 */
class AdminPageCalendarDayBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getContentOnglet(): string
    {
        // Récupération de la date
        $this->curStrDate = $this->initVar(self::CST_CAL_CURDAY, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_DAY,
            self::CST_CAL_CURDAY => $this->curStrDate
        ];
        $strLink = $this->getLink(self::LABEL_DAILY, UrlUtils::getAdminUrl($urlAttributes), '');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);

        // Récupération du contenu principal
        $strCards = $this->getCard();

        // Construction et renvoi du template
        $attributes = [
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_CALENDAR, $attributes);
    }
    
    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getCard(): string
    {
        $objCopsEventServices = new CopsEventServices();

        /////////////////////////////////////////
        // On récupère le premier jour de la semaine de la date courante
        $displayDate = $this->curStrDate;
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On récupère tous les events
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_DSTART => $displayDate,
                self::FIELD_DEND => $displayDate
            ],
            self::SQL_ORDER_BY => [self::FIELD_DSTART, self::FIELD_DEND],
            self::SQL_ORDER => [self::SQL_ORDER_ASC, self::SQL_ORDER_DESC]];
        $objsEventDate = $objCopsEventServices->getEventDates($attributes);

        $this->objsAlldayEventDate = [];
        $this->objsTodayEventDate = [];
        // On va trier les event "Allday" de ceux qui ne le sont pas.
        while (!empty($objsEventDate)) {
            $objEventDate = array_shift($objsEventDate);
            if ($objEventDate->getCopsEvent()->isAllDayEvent()) {
                $this->objsAlldayEventDate[] = $objEventDate;
            } else {
                $this->objsTodayEventDate[] = $objEventDate;
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On construit la colonne des horaires
        $strColumnHoraire = '';
        for ($h=0; $h<=23; ++$h) {
            $strColumnHoraire .= $this->getColumnHoraire($h);
        }
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On définit la semaine précédente et la semaine suivante
        $this->prevCurday = DateUtils::getDateAjout($this->curStrDate, [-1, 0, 0], self::FORMAT_DATE_YMD);
        $this->nextCurday = DateUtils::getDateAjout($this->curStrDate, [1, 0, 0], self::FORMAT_DATE_YMD);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Enrichissement du template
        $urlTemplate = self::WEB_PPFS_CAL_DAY;
        $attributes = [
            // Le Header de la journée
            $this->getRowHeader($displayDate),
            // Les events all-day de la journée
            $this->getRowAllDay($displayDate),
            // La première colonne contenant les horaires
            $strColumnHoraire,
            // La deuxième colonne avec les events de la journée
            $this->getRowHoraire($displayDate),
        ];
        $viewContent = $this->getRender($urlTemplate, $attributes);
        $calendarHeader = DateUtils::getStrDate(self::FORMAT_DATE_DMONTHY, $displayDate);
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);

        return $this->getDiv($mainContent, [self::ATTR_CLASS=>'col']);
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getRowHeader(string $displayDate): string
    {
        $strClass='';

        $url = '#';
        $aClass = 'fc-col-header-cell-cushion';
        $label = DateUtils::getStrDate('fd d fm', $displayDate);
        $divContent = $this->getLink($label, $url, $aClass);
        $thContent = $this->getDiv($divContent, [self::ATTR_CLASS=>'fc-scrollgrid-sync-inner']);
        $attributes = [
            self::ATTR_ROLE => 'columnheader',
            self::ATTR_CLASS => 'fc-col-header-cell '.self::CST_FC_DAY.' '.$strClass,
            self::ATTR_DATA_DATE => date(self::FORMAT_DATE_YMD, $tsDisplay)
        ];
        return $this->getBalise(self::TAG_TH, $thContent, $attributes);
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getRowAllDay(string $displayDate): string
    {
        $allDayEvents = $this->getAllDayEvents($displayDate);

        $botAttributes = [
            self::ATTR_CLASS => 'fc-daygrid-day-bottom',
            self::ATTR_STYLE => 'margin-top: 0px;'
        ];
        $divBottom = $this->getDiv('', $botAttributes);

        $divIn  = $this->getDiv($allDayEvents.$divBottom, [self::ATTR_CLASS=>'fc-daygrid-day-events']);
        $divIn .= $this->getDiv('', [self::ATTR_CLASS=>'fc-daygrid-day-bg']);

        $tdContent = $this->getDiv($divIn, [self::ATTR_CLASS=>'fc-daygrid-day-frame fc-scrollgrid-sync-inner']);
        $attributes = [
            self::ATTR_ROLE => 'gridcell',
            self::ATTR_CLASS => 'fc-daygrid-day fc-day ' . $this->getFcDayClass($displayDate),
            self::ATTR_DATA_DATE => $displayDate
        ];
        return $this->getBalise(self::TAG_TD, $tdContent, $attributes);
    }
    
    /**
     * @since v1.23.05.06
     * @version v1.23.05.07
     */
    public function getAllDayEvents($displayDate)
    {
        $strContent = '';
        while (!empty($this->objsAlldayEventDate)) {
            $objEventDate = array_shift($this->objsAlldayEventDate);
            $objBean = $objEventDate->getBean();
            $strContent .= $objBean->getCartouche(self::CST_CAL_DAY, $displayDate);
        }
        return $strContent;
    }

    /**
     * @since v1.23.05.06
     * @version v1.23.05.07
     */
    public function getRowHoraire(string $displayDate): string
    {
        $divContent  = $this->getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_BG]);
        $divContent .= $this->getDiv($this->getDayCell(), [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_EVENTS]);
        $divContent .= $this->getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_EVENTS]);
        $divContent .= $this->getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_NOW_IC]);
        
        $tdContent = $this->getDiv($divContent, [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_FRAME]);
        $tdAttributes = [
            self::ATTR_ROLE => self::CST_GRIDCELL,
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL.' '.self::CST_FC_DAY.' '.$this->getFcDayClass($displayDate),
            self::ATTR_DATA_DATE => date(self::FORMAT_DATE_YMD, $tsDisplay)
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
