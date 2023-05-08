<?php
namespace core\bean;

use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\UrlUtils;
use core\domain\MySQLClass;

/**
 * Classe AdminPageCalendarMonthBean
 * @author Hugues
 * @since v1.23.05.03
 * @version v1.23.05.07
 */
class AdminPageCalendarMonthBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.02
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
            self::CST_SUBONGLET => self::CST_CAL_MONTH,
            self::CST_CAL_CURDAY => $this->curStrDate
        ];
        $strLink = $this->getLink(self::LABEL_MONTHLY, UrlUtils::getAdminUrl($urlAttributes), '');
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
     * @since v1.23.05.03
     * @version v1.23.05.07
     */
    public function getCard(): string
    {
        /////////////////////////////////////////
        // On va afficher les journées du mois.
        // On affiche toujours 6 lignes.
        // On affiche du lundi au dimanche
        // La première ligne contient le premier jour du mois.
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On défini le premier jour de la semaine du premier jour du mois
        $startDate = DateUtils::getDateStartWeekMonth($this->curStrDate, self::FORMAT_DATE_YMD);
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On parcourt le mois
        $strContent = '';
        for ($w=0; $w<6; ++$w) {
            $trContent = '';
            for ($j=0; $j<7; ++$j) {
                $displayDate = DateUtils::getDateAjout($startDate, [$j+$w*7, 0, 0], self::FORMAT_DATE_YMD);
                $trContent .= $this->getMonthCell($displayDate, $j==0);
            }
            $strContent .= $this->getBalise(self::TAG_TR, $trContent);
        }
        $urlTemplate = self::WEB_PPFS_CAL_MONTH;
        $attributes = [
            // Les lignes à afficher
            $strContent,
        ];
        $viewContent = $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////

        // On définit le mois précédent et le mois suivant
        $this->prevCurday = DateUtils::getDateAjout($this->curStrDate, [0, -1, 0], self::FORMAT_DATE_YMD);
        $this->nextCurday = DateUtils::getDateAjout($this->curStrDate, [0, 1, 0], self::FORMAT_DATE_YMD);
        
        $calendarHeader = DateUtils::getStrDate('month y', $this->curStrDate);
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);
        
        return $this->getDiv($mainContent, [self::ATTR_CLASS=>'col']);
    }
    
    /**
     * @since v1.23.05.03
     * @version v1.23.05.07
     */
    public function getMonthCell(string $displayDate, bool $blnMonday): string
    {
        $strClass = $this->getFcDayClass($displayDate);

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_CAL_CURDAY => $displayDate
        ];
        
        // Construction de la cellule
        if ($blnMonday) {
            $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
            $aHref = UrlUtils::getAdminUrl($urlElements);
            $aClass = self::CST_FC_DAYGRID_DAY_NB.' '.self::CST_TEXT_WHITE;
            $divContent = $this->getLink(DateUtils::getStrDate('W', $displayDate), $aHref, $aClass);
            $divAttributes = [
                self::ATTR_CLASS => 'badge bg-primary',
                self::ATTR_STYLE => 'position: absolute; left: 2px; top: 2px'
            ];
            $strWeekLink = $this->getDiv($divContent, $divAttributes);
        } else {
            $strWeekLink = '';
        }
        
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
        $url = UrlUtils::getAdminUrl($urlElements);
        $strLink = $this->getLink(substr($displayDate, 8, 2), $url, self::CST_FC_DAYGRID_DAY_NB);
        $strContent  = $this->getDiv($strWeekLink.$strLink, [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_TOP]);

        $attr = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_EVENTS];
        $strContent .= $this->getDiv($this->getAllDayEvents($displayDate), $attr);

        $strContent .= $this->getDiv('', [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_BG]);
        
        $divAttributes = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_FRAME.' '.self::CST_FC_SCROLLGRID_SYNC_IN];
        $divContent = $this->getDiv($strContent, $divAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY.' '.self::CST_FC_DAY.' '.$strClass,
            self::ATTR_DATA_DATE => $displayDate
        ];
        return $this->getBalise(self::TAG_TD, $divContent, $tdAttributes);
    }
    
    /**
     * @since v1.23.05.03
     * @version v1.23.05.07
     */
    public function getAllDayEvents(string $displayDate): string
    {
        $strContent = '';
        /////////////////////////////////////////
        // On récupère tous les events du jour
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_DSTART => $displayDate,
                self::FIELD_DEND => $displayDate
            ],
            self::SQL_ORDER_BY => [self::FIELD_DSTART, self::FIELD_DEND],
            self::SQL_ORDER => [self::SQL_ORDER_ASC, self::SQL_ORDER_DESC]
        ];
        $this->objCopsEventServices = new CopsEventServices();
        $objsEventDate = $this->objCopsEventServices->getEventDates($attributes);
        $nbEvts = 0;
        // On va trier les event "Allday" de ceux qui ne le sont pas.
        while (!empty($objsEventDate)) {
            $objEventDate = array_shift($objsEventDate);
            if ($objEventDate->getCopsEvent()->isAllDayEvent()) {
                if ($objEventDate->getCopsEvent()->isFirstDay($displayDate)) {
                    $tag = self::CST_CAL_MONTH;
                    $strContent .= 'firstDay';
                    //$strContent .= $objCopsEventDate->getBean()->getCartouche($tag, $displayDate, $nbEvts);
                } elseif (DateUtils::isMonday($displayDate)) {
                    // On a un événement qui est couvert par la période mais dont le premier jour
                    // n'est pas sur la période. C'est un événement de la semaine précédente
                    // qui déborde sur la semaine affichée.
                    // On ne doit le traiter que si on est un lundi.
                    //$strContent .= $objCopsEventDate->getBean()->getCartouche(self::CST_CAL_WEEK, $displayDate, $nbEvts);
                    $strContent .= 'monday';
                } else {
                    // TODO
                    $strContent .= 'flag';
                }
                ++$nbEvts;
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

        return $strContent.$divBottom;
    }

    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     *
    public function getEvents($tsDisplay)
    {
        return '';
        // TODO
        /*
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID     => self::SQL_JOKER_SEARCH,
            self::FIELD_DSTART => date(self::FORMAT_DATE_YMD, $tsDisplay),
            self::FIELD_DEND   => date(self::FORMAT_DATE_YMD, $tsDisplay),
        );
        $CopsEventDates = $this->CopsEventServices->getCopsEventDates($attributes);

        while (!empty($CopsEventDates)) {
            $CopsEventDate = array_shift($CopsEventDates);
            if ($CopsEventDate->getField('dStart')==date(self::FORMAT_DATE_YMD, $tsDisplay)) {
                $strContent .= $CopsEventDate->getBean()->getEventDateDisplay($tsDisplay);
            }
        }
        *
    }
    */
}
