<?php
namespace core\bean;

use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\UrlUtils;
use core\domain\MySQLClass;

/**
 * Classe AdminPageCalendarWeekBean
 * @author Hugues
 * @since v1.23.05.04
 * @version v1.23.05.21
 */
class AdminPageCalendarWeekBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.04
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
            self::CST_SUBONGLET => self::CST_CAL_WEEK,
            self::CST_CAL_CURDAY => $this->curStrDate
        ];
        $strLink = $this->getLink(self::LABEL_WEEKLY, UrlUtils::getAdminUrl($urlAttributes), '');
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
     * @since v1.23.05.04
     * @version v1.23.05.07
     */
    public function getCard(): string
    {
        /////////////////////////////////////////
        // On va afficher les jours de la semaine.
        // On affiche toujours 1 ligne.
        // On affiche du lundi au dimanche
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On récupère le premier jour de la semaine de la date courante
        $firstWeekDay = DateUtils::getDateStartWeek($this->curStrDate, self::FORMAT_DATE_YMD);
        [$fY, $fm, $fd] = explode('-', $firstWeekDay);
        $lastWeekDay = DateUtils::getDateAjout($firstWeekDay, [6, 0, 0], self::FORMAT_DATE_YMD);
        [$lY, $lm,] = explode('-', $lastWeekDay);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On construit le header du tableau
        if ($fm==$lm) {
            // Si $fM et $lM sont identiques, la semaine complète est dans un même mois.
            // 3-9 Juin 2030
            $calendarHeader = $fd;
        } elseif ($fY!=$lY) {
            // Si $fY et $lY diffèrent, la semaine est à cheval sur deux années.
            // 26 Dec 2021 – 1 Jan 2022
            $calendarHeader  = DateUtils::getStrDate(self::FORMAT_DATE_DMONTHY, $firstWeekDay);
        } else {
            // Sinon, seuls les deux mois diffèrent, la semaine est à cheval sur deux mois.
            // 27 Mars - 2 Avr 2022
            $calendarHeader  = DateUtils::getStrDate('d month', $firstWeekDay);
        }
        $calendarHeader .= ' - '.DateUtils::getStrDate(self::FORMAT_DATE_DMONTHY, $lastWeekDay);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On construit la ligne contenant les dates de la semaine
        $strRowHeaders = $this->getRowHeader($firstWeekDay);
        // On construit la ligne contenant les events "Allday"
        // On stockera les eventDate rencontrés qui ne sont pas des "Allday";
        $this->objsEventDates = [];
        $strRowAllDay  = $this->getRowAllDay($firstWeekDay);
        // On construit les lignes et colonnes du tableau
        $strRowHoraire = $this->getRowHoraire($firstWeekDay);
        
        // On construit la colonne des horaires
        $strColumnHoraire = '';
        for ($h=0; $h<=23; ++$h) {
            $strColumnHoraire .= $this->getColumnHoraire($h);
        }
        /////////////////////////////////////////

        // On définit la semaine précédente et la semaine suivante
        $this->prevCurday = DateUtils::getDateAjout($this->curStrDate, [-7, 0, 0], self::FORMAT_DATE_YMD);
        $this->nextCurday = DateUtils::getDateAjout($this->curStrDate, [7, 0, 0], self::FORMAT_DATE_YMD);

        /////////////////////////////////////////
        $urlTemplate = self::WEB_PPFS_CAL_WEEK;
        $attributes = [
            // La première ligne du tableau avec les dates
            $strRowHeaders,
            // La deuxième ligne du tableau avec les events "all-day"
            $strRowAllDay,
            // La première colonne contenant les horaires
            $strColumnHoraire,
            // La deuxième colonne contenant les events
            $strRowHoraire,
        ];
        $viewContent = $this->getRender($urlTemplate, $attributes);
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);

        return $this->getDiv($mainContent, [self::ATTR_CLASS=>'col']);
    }

    /**
     * @since v1.23.05.04
     * @version v1.23.05.14
     */
    public function getRowHoraire(string $firstWeekDay): string
    {
        $strContent = '';
        // On parcourt la semaine
        for ($i=0; $i<7; ++$i) {
            $curDay = DateUtils::getDateAjout($firstWeekDay, [$i, 0, 0], self::FORMAT_DATE_YMD);

            $divContent  = $this->getDiv('', [self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL_BG]);
            $locAttributes = [self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL_EVENTS];
            $divContent .= $this->getDiv($this->getWeekCell($curDay), $locAttributes);
            $divContent .= $this->getDiv('', [self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL_EVENTS]);
            $divContent .= $this->getDiv('', [self::ATTR_CLASS => self::CST_FC_TIMEGRID_NOW_IC]);

            $tdContent = $this->getDiv($divContent, [self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL_FRAME]);
            $tdAttributes = [
                self::ATTR_ROLE => self::CST_GRIDCELL,
                self::ATTR_CLASS => self::CST_FC_TIMEGRID_COL.' '.self::CST_FC_DAY.' '.$this->getFcDayClass($curDay),
                self::ATTR_DATA_DATE => $curDay
            ];
            $strContent .= $this->getBalise(self::TAG_TD, $tdContent, $tdAttributes);
        }

        return $strContent;
    }
    
    /**
     * @since v1.23.05.04
     * @version v1.23.05.21
     */
    public function getRowAllDay(string $firstWeekDay): string
    {
        $objCopsEventServices = new CopsEventServices;
        $strContent = '';

        // On parcourt la semaine
        for ($i=0; $i<7; ++$i) {
            $tdContent = '';
            // On construit la chaine du jour concerné
            $curDay = DateUtils::getDateAjout($firstWeekDay, [$i, 0, 0], self::FORMAT_DATE_YMD);

            // On récupère tous les eventDates du jour
            $attributes = [
                self::SQL_WHERE_FILTERS => [self::FIELD_DSTART => $curDay, self::FIELD_DEND => $curDay],
                self::SQL_ORDER_BY => [self::FIELD_DSTART, self::FIELD_DEND],
                self::SQL_ORDER => [self::SQL_ORDER_ASC, self::SQL_ORDER_DESC]
            ];
            $objsEventDate = $objCopsEventServices->getEventDates($attributes);
            $nbEvts = 0;

            /////////////////////////////////////////
            // On va trier les event "Allday" de ceux qui ne le sont pas.
            while (!empty($objsEventDate)) {
                $objEventDate = array_shift($objsEventDate);
                if ($objEventDate->getCopsEvent()->isAllDayEvent()) {
                    if ($objEventDate->getCopsEvent()->isFirstDay($curDay) || DateUtils::isMonday($curDay)) {
                        $tdContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_WEEK, $curDay, $nbEvts);
                    }
                    ++$nbEvts;
                } else {
                    $this->objsEventDates[$curDay][] = $objEventDate;
                }
            }
            /////////////////////////////////////////

            /////////////////////////////////////////
            // On créé le div de fin de cellule
            $botAttributes = [
                self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_BTM,
                self::ATTR_STYLE => 'margin-top: '.(25*$nbEvts).'px;'
            ];
            $divBottom = $this->getDiv('', $botAttributes);
            /////////////////////////////////////////

            // Construction du contenu de la cellule
            $divContent  = $this->getDiv($tdContent.$divBottom, [self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_EVENTS]);
            $divContent .= $this->getDiv('', [self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_BG]);
            $divAttributes = [self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_FRAME.' '.self::CST_FC_SCROLLGRID_SYNC_IN];
            $tdContent = $this->getDiv($divContent, $divAttributes);

            // Construction de la cellule
            $tdAttributes = [
                self::ATTR_ROLE => self::CST_GRIDCELL,
                self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY.' '.self::CST_FC_DAY.' '.$this->getFcDayClass($curDay),
                self::ATTR_DATA_DATE => $curDay,
            ];
            $strContent .= $this->getBalise(self::TAG_TD, $tdContent, $tdAttributes);
        }

        return $strContent;
    }
    
    /**
     * @since v1.23.05.04
     * @version v1.23.05.07
     */
    public function getRowHeader(string $firstWeekDay): string
    {
        // On défini la base des différents éléments utilisés
        $strRowHeaders = '';
        $aStyle = self::CST_FC_COL_HEADER_CELL_CSH;
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_DAY,
        ];

        // On parcourt la semaine
        for ($i=0; $i<7; ++$i) {
            // On construit la chaine du jour concerné
            $curDay = DateUtils::getDateAjout($firstWeekDay, [$i, 0, 0], self::FORMAT_DATE_YMD);
            // On complète l'url
            $urlAttributes[self::CST_CAL_CURDAY] = $curDay;
            $url = UrlUtils::getAdminUrl($urlAttributes);
            // On construit le lien
            $aContent = DateUtils::getStrDate('w d/m', $curDay);
            $divContent = $this->getLink($aContent, $url, $aStyle, ['aria-label' => $curDay]);
            // On construit la div contenant le lien
            $thContent = $this->getDiv($divContent, [self::ATTR_CLASS => self::CST_FC_SCROLLGRID_SYNC_IN]);
            // On défini les attributs de la cellule
            $strClass = $this->getFcDayClass($curDay);
            $thAttributes = [
                self::ATTR_ROLE => self::CST_COLUMNHEADER,
                self::ATTR_CLASS => self::CST_FC_COL_HEADER_CELL.' '.self::CST_FC_DAY.' '.$strClass,
                self::ATTR_DATA_DATE => $curDay,
            ];
            // On concatène la cellule.
            $strRowHeaders .= $this->getBalise(self::TAG_TH, $thContent, $thAttributes);
        }

        return $strRowHeaders;
    }
    
    /**
     * @since v1.23.05.05
     * @version v1.23.05.14
     */
    public function getWeekCell(string $curDay): string
    {
        $strContent = '';
        if (isset($this->objsEventDates[$curDay])) {
            foreach ($this->objsEventDates[$curDay] as $objEventDate) {
                $strContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_WEEK, $curDay);
            }
        }
        return $strContent;
    }

}
