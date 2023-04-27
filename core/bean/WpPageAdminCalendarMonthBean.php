<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * Classe WpPageAdminCalendarMonthBean
 * @author Hugues
 * @since 1.22.11.21
 * @version v1.23.04.30
 */
class WpPageAdminCalendarMonthBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_MONTH;
        $this->titreSubOnglet = self::LABEL_MONTHLY;
        /////////////////////////////////////////
        // Définition des services
        $this->objCopsEventServices = new CopsEventServices();

        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
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
        // On va afficher les journées du mois.
        // On affiche toujours 6 lignes.
        // On affiche du lundi au dimanche
        // La première ligne contient le premier jour du mois.
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On récupère le jour courant
        [$m, $d, $y] = explode('-', (string) $this->curStrDate);
        // On défini le premier jour du mois
        [$fN, $fd, $fm, $fY] = explode(' ', date('N d m Y', mktime(0, 0, 0, $m, 1, $y)));
        // Si le premier jour n'est pas un lundi, on recherche le lundi de sa semaine pour débuter l'affichage.
        if ($fN!=1) {
            [$fN, $fd, $fm, $fY] = explode(' ', date('N d m Y', mktime(0, 0, 0, $m, 2-$fN, $y)));
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On parcourt le mois
        $strContent = '';
        for ($w=0; $w<6; ++$w) {
            $trContent = '';
            for ($j=0; $j<7; ++$j) {
                $tsDisplay = mktime(0, 0, 0, $fm, $fd+($j+$w*7), $fY);
                $trContent .= $this->getMonthCell($tsDisplay, $j==0);
            }
            $strContent .= $this->getBalise(self::TAG_TR, $trContent);
        }
        $urlTemplate = self::PF_SECTION_CAL_MONTH;
        $attributes = [
            // Les lignes à afficher
            $strContent,
        ];
        $viewContent = $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////
        
        $this->prevCurday = date(self::FORMAT_DATE_MDY, mktime(0, 0, 0, $m-1, $d, $y));
        $this->nextCurday = date(self::FORMAT_DATE_MDY, mktime(0, 0, 0, $m+1, $d, $y));
        
        $tsCopsDate = mktime(0, 0, 0, $m, $d, $y);
        $calendarHeader = DateUtils::arrFullMonths[date('m', $tsCopsDate)*1].date(' Y', $tsCopsDate);
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);
        
        $urlTemplate = self::PF_SECTION_ONGLET;
        $attributes = [
            // L'id de la page
            'section-cal-month',
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
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getMonthCell($tsDisplay, $blnMonday)
    {
        $strClass = $this->getFcDayClass($tsDisplay);

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_CAL_CURDAY => date(self::FORMAT_DATE_MDY, $tsDisplay)
        ];
        
        // Construction de la cellule
        if ($blnMonday) {
            $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
            $aHref = $this->getUrl($urlElements);
            $aClass = 'fc-daygrid-day-number text-white';
            $divContent = $this->getLink(date('W', $tsDisplay), $aHref, $aClass);
            $divAttributes = [
                self::ATTR_CLASS => 'badge bg-primary',
                self::ATTR_STYLE => 'position: absolute; left: 2px; top: 2px'
            ];
            $strWeekLink = $this->getDiv($divContent, $divAttributes);
        } else {
            $strWeekLink = '';
        }
        
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
        $url = $this->getUrl($urlElements);
        $strLink = $this->getLink(date('d', $tsDisplay), $url, 'fc-daygrid-day-number text-white');
        
        $strContent  = $this->getDiv($strWeekLink.$strLink, [self::ATTR_CLASS=>'fc-daygrid-day-top']);
        $attr = [self::ATTR_CLASS=>'fc-daygrid-day-events'];
        $strContent .= $this->getDiv($this->getAllDayEvents($tsDisplay), $attr);
        $strContent .= $this->getDiv('', [self::ATTR_CLASS=>'fc-daygrid-day-bg']);
        
        $divAttributes = [self::ATTR_CLASS=>'fc-daygrid-day-frame fc-scrollgrid-sync-inner'];
        $divContent = $this->getDiv($strContent, $divAttributes);
        
        $tdAttributes = [
            self::ATTR_CLASS => 'fc-daygrid-day fc-day '.$strClass,
            'data-date' => date(self::FORMAT_DATE_YMD, $tsDisplay)
        ];
        return $this->getBalise(self::TAG_TD, $divContent, $tdAttributes);
    }
    
    /**
     * @since v1.22.11.22
     * @version v1.22.11.22
     */
    public function getAllDayEvents($tsDisplay)
    {
        $strContent = '';
        /////////////////////////////////////////
        // On récupère tous les events du jour
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => '%',
                self::FIELD_DSTART => date(self::FORMAT_DATE_YMD, $tsDisplay),
                self::FIELD_DEND => date(self::FORMAT_DATE_YMD, $tsDisplay)
            ],
            self::SQL_ORDER_BY => ['dStart', 'dEnd'],
            self::SQL_ORDER => ['ASC', 'DESC']
        ];
        $objsCopsEventDate = $this->objCopsEventServices->getCopsEventDates($attributes);
        $nbEvts = 0;
        // On va trier les event "Allday" de ceux qui ne le sont pas.
        while (!empty($objsCopsEventDate)) {
            $objCopsEventDate = array_shift($objsCopsEventDate);
            if ($objCopsEventDate->getCopsEvent()->isAllDayEvent()) {
                if ($objCopsEventDate->getCopsEvent()->isFirstDay($tsDisplay)) {
                    $tag = self::CST_CAL_MONTH;
                    $strContent .= $objCopsEventDate->getBean()->getCartouche($tag, $tsDisplay, $nbEvts);
                } elseif (date('N', $tsDisplay)==1) {
                    // On a un événement qui est couvert par la période mais dont le premier jour
                    // n'est pas sur la période. C'est un événement de la semaine précédente
                    // qui déborde sur la semaine affichée.
                    // On ne doit le traiter que si on est un lundi.
                    $strContent .= $objCopsEventDate->getBean()->getCartouche(self::CST_CAL_WEEK, $tsDisplay, $nbEvts);
                } else {
                    // TODO
                }
                ++$nbEvts;
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On créé le div de fin de cellule
        $botAttributes = [
            self::ATTR_CLASS => 'fc-daygrid-day-bottom',
            self::ATTR_STYLE => 'margin-top: '.(25*$nbEvts).'px;'
        ];
        $divBottom = $this->getDiv('', $botAttributes);
        /////////////////////////////////////////

        return $strContent.$divBottom;
    }

    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
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
        */
    }
}
