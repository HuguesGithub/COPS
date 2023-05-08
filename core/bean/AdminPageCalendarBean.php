<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalendrierBean
 * @author Hugues
 * @since v1.23.05.01
 * @version v1.23.05.07
 */
class AdminPageCalendarBean extends AdminPageBean
{
    /**
     * @since v1.23.05.01
     * @version v1.23.05.07
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (static::fromGet(self::CST_SUBONGLET)) {
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
     * @version v1.23.05.07
     */
    public function getContentPage(): string
    {
        // On récupère l'éventuel subonglet.
        $curSubOnglet = $this->initVar(self::CST_SUBONGLET);
        // On récupère la date du jour.
        $curStrDate = $this->initVar(self::CST_CAL_CURDAY, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME      => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_CAL_MONTH => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_MONTHLY],
            self::CST_CAL_WEEK  => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_WEEKLY],
            self::CST_CAL_DAY   => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_DAILY],
            self::CST_CAL_EVENT => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_EVENTS]
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $urlAttributes = [self::CST_ONGLET=>self::ONGLET_CALENDAR, self::CST_CAL_CURDAY=>$curStrDate];
        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $urlAttributes[self::CST_SUBONGLET] = $slugSubOnglet;

            $blnActive = ($curSubOnglet==$slugSubOnglet || $curSubOnglet=='' && $slugSubOnglet==self::CST_HOME);
            $strLink = $this->getLink(
                $arrData[self::FIELD_LABEL],
                UrlUtils::getAdminUrl($urlAttributes),
                self::NAV_LINK.($blnActive ? ' '.self::CST_ACTIVE : '')
            );
            $strLis .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>self::NAV_ITEM]);
        }
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $this->styleBreadCrumbs = 'breadcrumb-item float-left';
        $strLink = $this->getLink('<i class="feather icon-home"></i>', UrlUtils::getAdminUrl(), '');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);
        $urlAttributes = [self::CST_ONGLET=>self::ONGLET_CALENDAR];
        $strLink = $this->getLink(self::LABEL_CALENDAR, UrlUtils::getAdminUrl($urlAttributes), '');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);
        /////////////////////////////////////////

        return $this->getBalise(self::TAG_UL, $strLis, $attributes);
    }

    /**
     * @since v1.23.05.01
     * @version v1.23.05.07
     */
    public function getContentOnglet(): string
    {
        return 'Default. Specific getContentOnglet() to be defined.';
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
     * @version v1.23.05.07
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
        if (substr($strDate, 5, 2)!=substr($strToday, 5, 2)) {
            $strClass .= ' '.self::CST_FC_DAY_OTHER;
        }
        ///////////////////////////////////////////////////
        return $strClass;
    }


    /**
     * @since v1.23.05.03
     * @version v1.23.05.07
     */
    public function getColumnHoraire($h)
    {
        $hPadded = str_pad((string) $h, 2, '0', STR_PAD_LEFT);
        $cushionAttributes = [self::ATTR_CLASS=>'fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion'];
        $shrinkCushion = $this->getDiv(date('ga', mktime($h, 0, 0)), $cushionAttributes);
        $frameAttributes = [self::ATTR_CLASS=>'fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame'];
        $shrinkFrame = $this->getDiv($shrinkCushion, $frameAttributes);
        $tdAttributes = [
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink',
            'data-time' => $hPadded.':00:00'
        ];
        $firstRow = $this->getBalise(self::TAG_TD, $shrinkFrame, $tdAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane',
            'data-time' => $hPadded.':00:00'
        ];
        $firstRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00'
        ];
        $secondRow = $this->getBalise(self::TAG_TD, '', $tdAttributes);
        $tdAttributes = [
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00'
        ];
        $secondRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        return $this->getBalise(self::TAG_TR, $firstRow).$this->getBalise(self::TAG_TR, $secondRow);
    }
}
