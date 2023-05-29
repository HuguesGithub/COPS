<?php
namespace core\bean;

use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe AdminPageCalendarDayBean
 * @author Hugues
 * @since 1.22.11.21
 * @version v1.23.05.28
 */
class AdminPageCalendarDayBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.05
     * @version v1.23.05.28
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
        $strLink = HtmlUtils::getLink(self::LABEL_DAILY, UrlUtils::getAdminUrl($urlAttributes), '');
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
     * @version v1.23.05.28
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
            if ($objEventDate->getEvent()->isAllDayEvent()) {
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

        return HtmlUtils::getDiv($mainContent, [self::ATTR_CLASS=>'col']);
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.28
     */
    public function getRowHeader(string $displayDate): string
    {
        $url = '#';
        $aClass = self::CST_FC_COL_HEADER_CELL_CSH;
        $label = DateUtils::getStrDate('fd d fm', $displayDate);
        $divContent = HtmlUtils::getLink($label, $url, $aClass);
        $thContent = HtmlUtils::getDiv($divContent, [self::ATTR_CLASS=>self::CST_FC_SCROLLGRID_SYNC_IN]);
        $attributes = [
            self::ATTR_ROLE => self::CST_COLUMNHEADER,
            self::ATTR_CLASS => self::CST_FC_COL_HEADER_CELL.' '.self::CST_FC_DAY,
            self::ATTR_DATA_DATE => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $displayDate)
        ];
        return HtmlUtils::getTh($thContent, $attributes);
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getRowAllDay(string $displayDate): string
    {
        $allDayEvents = $this->getAllDayEvents($displayDate);

        $botAttributes = [
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_BTM,
            self::ATTR_STYLE => 'margin-top: 0px;'
        ];
        $divBottom = HtmlUtils::getDiv('', $botAttributes);

        $divIn  = HtmlUtils::getDiv($allDayEvents.$divBottom, [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_EVENTS]);
        $divIn .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_BG]);

        $tdAttributes = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_FRAME.' '.self::CST_FC_SCROLLGRID_SYNC_IN];
        $tdContent = HtmlUtils::getDiv($divIn, $tdAttributes);

        $attributes = [
            self::ATTR_ROLE => self::CST_GRIDCELL,
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY.' '.self::CST_FC_DAY.' ' . $this->getFcDayClass($displayDate),
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
     * @version v1.23.05.14
     */
    public function getRowHoraire(string $displayDate): string
    {
        $divContent  = HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_BG]);
        $locAttributes = [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_EVENTS];
        $divContent .= HtmlUtils::getDiv($this->getDayCell($displayDate), $locAttributes);
        $divContent .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_EVENTS]);
        $divContent .= HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_NOW_IC]);
        
        $tdContent = HtmlUtils::getDiv($divContent, [self::ATTR_CLASS=>self::CST_FC_TIMEGRID_COL_FRAME]);
        $tdAttributes = [
            self::ATTR_ROLE => self::CST_GRIDCELL,
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL.' '.self::CST_FC_DAY.' '.$this->getFcDayClass($displayDate),
            self::ATTR_DATA_DATE => DateUtils::getStrDate(self::FORMAT_DATE_YMD, $displayDate)
        ];
        return $this->getBalise(self::TAG_TD, $tdContent, $tdAttributes);
    }

    /**
     * @since v1.23.05.13
     * @version v1.23.05.14
     */
    public function getDayCell(string $curDay): string
    {
        $strContent = '';
        if (isset($this->objsTodayEventDate)) {
            foreach ($this->objsTodayEventDate as $objEventDate) {
                $strContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_WEEK, $curDay);
            }
        }
        return $strContent;
    }
}
