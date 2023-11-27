<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalendrierBean
 * @author Hugues
 * @since v1.23.05.01
 * @version v1.23.12.02
 */
class AdminPageCalendarBean extends AdminPageBean
{
    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function __construct()
    {
        $this->pageTitle = self::LABEL_CALENDAR;
        $this->pageSubTitle = 'Gestion de la partie administrative du calendrier';
    }

    /**
     * @since v1.23.05.01
     * @version v1.23.06.25
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (SessionUtils::fromGet(self::CST_SUBONGLET)) {
            self::CST_CAL_EVENT => new AdminPageCalendarEventBean(),
            self::CST_CAL_DAY => new AdminPageCalendarDayBean(),
            self::CST_CAL_WEEK => new AdminPageCalendarWeekBean(),
            self::CST_CAL_MONTH => new AdminPageCalendarMonthBean(),
            default => new AdminPageCalendarHomeBean(),
        };
        ///////////////////////////////////////////:
        return $objBean->getContentOnglet();
    }

    /**
     * @since v1.23.05.01
     * @version v1.23.12.02
     */
    public function getContentPage(): string
    {
        // On récupère la date du jour.
        $this->curStrDate = $this->initVar(self::CST_CAL_CURDAY, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME      => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_CAL_MONTH => [self::FIELD_ICON => self::I_CALENDAR, self::FIELD_LABEL => self::LABEL_MONTHLY],
            self::CST_CAL_WEEK  => [self::FIELD_ICON => self::I_CALENDAR_WEEK, self::FIELD_LABEL => self::LABEL_WEEKLY],
            self::CST_CAL_DAY   => [self::FIELD_ICON => self::I_CALENDAR_DAYS, self::FIELD_LABEL => self::LABEL_DAILY],
            self::CST_CAL_EVENT => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_EVENTS]
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_CALENDAR, self::CST_CAL_CURDAY=>$this->curStrDate];
        $strLis = $this->buildTabs();
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return HtmlUtils::getBalise(self::TAG_UL, $strLis, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_CALENDAR];
        $strLink = HtmlUtils::getLink(self::LABEL_CALENDAR, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }
 
    /**
     * @since v1.23.07.15
     * @version v1.23.07.22
     */
    public function getContentOnglet(): string
    {
        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        // Récupération du contenu principal
        $strCards = $this->getCard();

        // Construction et renvoi du template
        $attributes = [
            $this->pageTitle,
            $this->pageSubTitle,
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_DEFAULT, $attributes);
    }


    /**
     * @since v1.23.05.03
     * @version v1.23.05.07
     */
    public function getSectionCalendar(string $calendarHeader, string $viewContent): string
    {
        /////////////////////////////////////////
        // Définition des urls pour les boutons
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => $this->initVar(self::CST_SUBONGLET),
        ];
        $urlToday = UrlUtils::getAdminUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $this->prevCurday;
        $urlPrev  = UrlUtils::getAdminUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $this->nextCurday;
        $urlNext  = UrlUtils::getAdminUrl($urlElements);
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        $urlTemplate = self::WEB_PAFS_CALENDAR;
        $attributes = [
            // L'url pour accéder au mois précédent
            $urlPrev,
            // L'url pour accéder au mois suivant
            $urlNext,
            // L'url pour accéder au mois courant
            $urlToday,
            // Le bandeau pour indiquer l'intervalle visionné
            $calendarHeader,
            // Le contenu du calendrier à visionner
            $viewContent,
        ];
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }


    /**
     * @since v1.23.05.03
     * @version v1.23.05.28
     */
    public function getFcDayClass(string $strDate): string
    {
        // On récupère le timestamp du jour COPS courant
        $strToday = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);

        ///////////////////////////////////////////////////
        // On construit la classe de la cellule avec le jour de la semaine
        $strClass = self::CST_FC_DAY.'-'.strtolower(DateUtils::getStrDate('sduk', $strDate));
        // La date passée, présente ou future
        // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
        if ($strDate==$strToday) {
            $strClass .= ' '.self::CST_FC_DAY_TODAY;
        } elseif ($strDate<=$strToday) {
            $strClass .= ' '.self::CST_FC_DAY_PAST;
        } else {
            $strClass .= ' '.self::CST_FC_DAY_FUTURE;
        }
        // Un autre mois ou non
        // si le jour est dans un autre mois : fc-day-other
        if (substr($strDate, 5, 2)!=substr($strToday, 5, 2) || substr($strDate, 0, 4)!=substr($strToday, 0, 4)) {
            $strClass .= ' '.self::CST_FC_DAY_OTHER;
        }
        ///////////////////////////////////////////////////
        return $strClass;
    }

    /**
     * @since v1.23.05.03
     * @version v1.23.07.22
     */
    public function getColumnHoraire(int $h): string
    {
        $hPadded = str_pad((string) $h, 2, '0', STR_PAD_LEFT);
        $cushionAttributes = [
            self::ATTR_CLASS=>self::CST_FC_TIMEGRID_SLOT_LABEL_CUSHION.' '.self::CST_FC_SCROLLGRID_SHRINK_CUSHION
        ];
        $shrinkCushion = HtmlUtils::getDiv(DateUtils::getStrDate('ga', mktime($h, 0, 0)), $cushionAttributes);
        $frameAttributes = [
            self::ATTR_CLASS=>self::CST_FC_TIMEGRID_SLOT_LABEL_FRAME.' '.self::CST_FC_SCROLLGRID_SHRINK_FRAME
        ];
        $shrinkFrame = HtmlUtils::getDiv($shrinkCushion, $frameAttributes);
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_SLOT_LABEL.' '.self::CST_FC_SCROLLGRID_SHRINK,
            self::ATTR_DATA => [
                self::ATTR_TIME => $hPadded.':00:00'
            ],
        ];
        $firstRow = HtmlUtils::getBalise(self::TAG_TD, $shrinkFrame, $tdAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_SLOT_LANE,
            self::ATTR_DATA => [
                self::ATTR_TIME => $hPadded.':00:00'
            ],
        ];
        $firstRow .= HtmlUtils::getBalise(self::TAG_TD, '', $tdAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_SLOT_LABEL.' '.self::CST_FC_TIMEGRID_SLOT_MINOR,
            self::ATTR_DATA => [
                self::ATTR_TIME => $hPadded.':30:00'
            ],
        ];
        $secondRow = HtmlUtils::getBalise(self::TAG_TD, '', $tdAttributes);
        $tdAttributes = [
            self::ATTR_CLASS => self::CST_FC_TIMEGRID_SLOT_LANE.' '.self::CST_FC_TIMEGRID_SLOT_MINOR,
            self::ATTR_DATA => [
                self::ATTR_TIME => $hPadded.':30:00'
            ],
        ];
        $secondRow .= HtmlUtils::getBalise(self::TAG_TD, '', $tdAttributes);
        
        return HtmlUtils::getBalise(self::TAG_TR, $firstRow).HtmlUtils::getBalise(self::TAG_TR, $secondRow);
    }
}
