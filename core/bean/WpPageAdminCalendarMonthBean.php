<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarMonthBean
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.21
 */
class WpPageAdminCalendarMonthBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_MONTH;
        $this->titreSubOnglet = 'Mensuel';
    
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
        // On va afficher les journées du mois.
        // On affiche toujours 6 lignes.
        // On affiche du lundi au dimanche
        // La première ligne contient le premier jour du mois.
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On récupère le jour courant
        list($m, $d, $y) = explode('-', $this->curStrDate);
        // On défini le premier jour du mois
        list($fN, $fd, $fm, $fY) = explode(' ', date('N d m Y', mktime(0, 0, 0, $m, 1, $y)));
        // Si le premier jour n'est pas un lundi, on recherche le lundi de sa semaine pour débuter l'affichage.
        if ($fN!=1) {
            list($fN, $fd, $fm, $fY) = explode(' ', date('N d m Y', mktime(0, 0, 0, $m, 2-$fN, $y)));
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On parcourt le mois
        $strContent = '';
        for ($w=0; $w<6; $w++) {
            $trContent = '';
            for ($j=0; $j<7; $j++) {
                $tsDisplay = mktime(0, 0, 0, $fm, $fd+($j+$w*7), $fY);
                $trContent .= $this->getMonthCell($tsDisplay, $j==0);
            }
            $strContent .= $this->getBalise(self::TAG_TR, $trContent);
        }
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-month.php';
        $attributes = array(
            // Les lignes à afficher
            $strContent,
        );
        $viewContent = $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////
        
        $calendarHeader = $this->arrFullMonths[date('m', mktime(1, 0, 0, $m, $d, $y))*1].date(' Y', mktime(1, 0, 0, $m, $d, $y));// Juin 2030
        $mainContent = $this->getSectionCalendar($calendarHeader, $viewContent);
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-cal-month',
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
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getMonthCell($tsDisplay, $blnMonday)
    {
        $strClass = $this->getFcDayClass($tsDisplay);

        $urlElements = array(
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_CAL_CURDAY => date('m-d-Y', $tsDisplay),
        );
        
        // Construction de la cellule
		if ($blnMonday) {
		    $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
		    $aHref = $this->getUrl($urlElements);
			$aClass = 'fc-daygrid-day-number text-white float-left';
			$aAttributes = array(
				self::ATTR_STYLE => 'position: absolute; left: 0;',
			);
			$strWeekLink = $this->getLink(date('W', $tsDisplay), $aHref, $aClass, $aAttributes);
		} else {
			$strWeekLink = '';
		}
		
		$urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
		$url = $this->getUrl($urlElements);
        $strLink = $this->getLink(date('d', $tsDisplay), $url, 'fc-daygrid-day-number text-white');
		
        $strContent  = $this->getDiv($strWeekLink.$strLink, array(self::ATTR_CLASS=>'fc-daygrid-day-top'));
        $strContent .= $this->getDiv($this->getEvents($tsDisplay), array(self::ATTR_CLASS=>'fc-daygrid-day-events'));
        $strContent .= $this->getDiv('', array(self::ATTR_CLASS=>'fc-daygrid-day-bg'));
        
        $divAttributes = array(self::ATTR_CLASS=>'fc-daygrid-day-frame fc-scrollgrid-sync-inner');
        $divContent = $this->getDiv($strContent, $divAttributes);
        
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-daygrid-day fc-day '.$strClass,
            'data-date' => date('Y-m-d', $tsDisplay),
        );
        $strCellContent = $this->getBalise(self::TAG_TD, $divContent, $tdAttributes);
        
        return $strCellContent;
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
            self::FIELD_DSTART => date('Y-m-d', $tsDisplay),
            self::FIELD_DEND   => date('Y-m-d', $tsDisplay),
        );
        $CopsEventDates = $this->CopsEventServices->getCopsEventDates($attributes);

        while (!empty($CopsEventDates)) {
            $CopsEventDate = array_shift($CopsEventDates);
            if ($CopsEventDate->getField('dStart')==date('Y-m-d', $tsDisplay)) {
                $strContent .= $CopsEventDate->getBean()->getEventDateDisplay($tsDisplay);
            }
        }
        */
    }
}
