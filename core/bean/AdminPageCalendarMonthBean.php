<?php
namespace core\bean;

use core\domain\MySQLClass;
use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe AdminPageCalendarMonthBean
 * @author Hugues
 * @since v1.23.05.03
 * @version v1.23.08.12
 */
class AdminPageCalendarMonthBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.02
     * @version v1.23.07.22
     */
    public function getContentOnglet(): string
    {
        // Récupération de la date
        $this->curStrDate = $this->initVar(self::CST_CAL_CURDAY, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));
        return parent::getContentOnglet();
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes[self::CST_SUBONGLET]  = self::CST_CAL_MONTH;
        $this->urlAttributes[self::CST_CAL_CURDAY] = $this->curStrDate;
        $strLink = HtmlUtils::getLink(self::LABEL_MONTHLY, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.05.03
     * @version v1.23.05.22
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
            $strContent .= HtmlUtils::getBalise(self::TAG_TR, $trContent);
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
        
        return HtmlUtils::getDiv($mainContent, [self::ATTR_CLASS=>'col']);
    }
    
    /**
     * @since v1.23.05.03
     * @version v1.23.07.22
     */
    public function getMonthCell(string $displayDate, bool $blnMonday): string
    {
        $strClass = $this->getFcDayClass($displayDate);

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_CAL_CURDAY => $displayDate
        ];
        
        // Construction de la cellule

        //////////////////////////////////////////////////////////////////////////////////
        // Si on est un lundi, on doit ajouter le numéro et le lien de la semaine
        if ($blnMonday) {
            $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
            $aHref = UrlUtils::getAdminUrl($urlElements);
            $aClass = self::CST_FC_DAYGRID_DAY_NB.' '.self::CST_TEXT_WHITE;
            $divContent = HtmlUtils::getLink(DateUtils::getStrDate('W', $displayDate), $aHref, $aClass);
            $divAttributes = [
                self::ATTR_CLASS => 'badge bg-primary',
                self::ATTR_STYLE => 'position: absolute; left: 2px; top: 2px'
            ];
            $strWeekLink = HtmlUtils::getDiv($divContent, $divAttributes);
        } else {
            $strWeekLink = '';
        }
        //////////////////////////////////////////////////////////////////////////////////
        
        //////////////////////////////////////////////////////////////////////////////////
        // Le jour et le lien vers celui-ci
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
        $url = UrlUtils::getAdminUrl($urlElements);
        $strLink = HtmlUtils::getLink(substr($displayDate, 8, 2), $url, self::CST_FC_DAYGRID_DAY_NB);
        $strContent  = HtmlUtils::getDiv($strWeekLink.$strLink, [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_TOP]);
        //////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////
        // Gestion des événements
        // D'abord les événements qui prenennt toute la journée
        // Ou les "long events" sur plusieurs jours
        $attr = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_EVENTS];
        $strContent .= HtmlUtils::getDiv($this->getAllDayEvents($displayDate), $attr);
        // Puis ceux de la journée
        $locAttributes = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_BG];
        $strContent .= HtmlUtils::getDiv($this->getDayEvents($displayDate), $locAttributes);
        //////////////////////////////////////////////////////////////////////////////////
        
        $divAttributes = [self::ATTR_CLASS=>self::CST_FC_DAYGRID_DAY_FRAME.' '.self::CST_FC_SCROLLGRID_SYNC_IN];
        $divContent = HtmlUtils::getDiv($strContent, $divAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY.' '.self::CST_FC_DAY.' '.$strClass,
            self::ATTR_DATA_DATE => $displayDate
        ];
        return HtmlUtils::getBalise(self::TAG_TD, $divContent, $tdAttributes);
    }
    
    /**
     * @since v1.23.05.03
     * @version v1.23.08.12
     */
    public function getAllDayEvents(string $displayDate): string
    {
        $this->objsDayEvent = [];
        $strContent = '';
        /////////////////////////////////////////
        // On récupère tous les events du jour
        $attributes = [
            self::FIELD_DSTART => $displayDate,
            self::FIELD_DEND => $displayDate,
            self::SQL_ORDER_BY => [self::FIELD_DSTART, self::FIELD_DEND, self::FIELD_TSTART],
            self::SQL_ORDER => [self::SQL_ORDER_ASC, self::SQL_ORDER_DESC, self::SQL_ORDER_ASC]
        ];
        $this->objCopsEventServices = new CopsEventServices();
        $objsEventDate = $this->objCopsEventServices->getEventDates($attributes);
        $nbEvts = 0;
        // On va trier les event "Allday" de ceux qui ne le sont pas.
        while (!empty($objsEventDate)) {
            $objEventDate = array_shift($objsEventDate);
            if ($objEventDate->getEvent()->isAllDayEvent()) {
                if ($objEventDate->isFirstDay($displayDate)) {
                    $strContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_MONTH, $displayDate, $nbEvts);
                } elseif (DateUtils::isMonday($displayDate)) {
                    // On a un événement qui est couvert par la période mais dont le premier jour
                    // n'est pas sur la période. C'est un événement de la semaine précédente
                    // qui déborde sur la semaine affichée.
                    // On ne doit le traiter que si on est un lundi.
                    $strContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_MONTH, $displayDate, $nbEvts);
                } else {
                    // TODO
                }
                ++$nbEvts;
            } else {
                $dateDebut = $objEventDate->getField(self::FIELD_DSTART);
                if (!isset($this->objsDayEvent[$dateDebut])) {
                    $this->objsDayEvent[$dateDebut] = [];
                }
                $this->objsDayEvent[$dateDebut][] = $objEventDate;
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On créé le div de fin de cellule
        $botAttributes = [
            self::ATTR_CLASS => self::CST_FC_DAYGRID_DAY_BTM,
            self::ATTR_STYLE => 'margin-top: '.(25*$nbEvts).'px;'
        ];
        $divBottom = HtmlUtils::getDiv('', $botAttributes);
        /////////////////////////////////////////

        return $strContent.$divBottom;
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getDayEvents(string $displayDate): string
    {
        $objsEventDate = $this->objsDayEvent[$displayDate];

        $tdContent = '';
        while (!empty($objsEventDate)) {
            $objEventDate = array_shift($objsEventDate);
            $tdContent .= $objEventDate->getBean()->getCartouche(self::CST_CAL_MONTH, $displayDate);
        }
        return $tdContent;
    }

}
