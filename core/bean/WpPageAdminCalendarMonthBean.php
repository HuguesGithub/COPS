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
        
        $prevCurday = date('m-d-Y', mktime(1, 0, 0, $m-1, $d, $y));
        $nextCurday = date('m-d-Y', mktime(1, 0, 0, $m+1, $d, $y));
        
        $urlBase  = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_CALENDAR.self::CST_AMP.self::CST_SUBONGLET.'=';
        $urlToday = $urlBase.$this->slugSubOnglet;
        $urlMonth = $urlBase.self::CST_CAL_MONTH.self::CST_AMP.self::CST_CAL_CURDAY.'='.substr($this->curStrDate, 0, 10);
        $urlWeek  = $urlBase.self::CST_CAL_WEEK.self::CST_AMP.self::CST_CAL_CURDAY.'='.substr($this->curStrDate, 0, 10);
        $urlDay   = $urlBase.self::CST_CAL_DAY.self::CST_AMP.self::CST_CAL_CURDAY.'='.substr($this->curStrDate, 0, 10);
        $urlPrev  = $urlBase.$this->slugSubOnglet.self::CST_AMP.self::CST_CAL_CURDAY.'='.$prevCurday;
        $urlNext  = $urlBase.$this->slugSubOnglet.self::CST_AMP.self::CST_CAL_CURDAY.'='.$nextCurday;
        
        $calendarHeader = $this->arrFullMonths[date('m', mktime(1, 0, 0, $m, $d, $y))*1].date(' Y', mktime(1, 0, 0, $m, $d, $y));// Juin 2030
        
        /////////////////////////////////////////
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar.php';
        $attributes = array(
            // L'url pour accéder au mois/semaine/jour précédent
            $urlPrev,
            // L'url pour accéder au mois/semaine/jour suivant
            $urlNext,
            // L'url pour accéder au mois/semaine/jour courant
            $urlToday,
            // Le bandeau pour indiquer l'intervalle (mois/semaine/jour) visionné
            $calendarHeader,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_MONTH ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans le mois
            $urlMonth,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_WEEK ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans la semaine
            $urlWeek,
            // Permet de définir si le bouton est celui de la vue en cours
            ($this->slugSubOnglet==self::CST_CAL_DAY ? ' '.self::CST_ACTIVE : ''),
            // L'url pour visualiser le jour courant dans le jour
            $urlDay,
            // Le contenu du calendrier à visionner
            $viewContent,
        );
        $mainContent = $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////
        
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
    
        $urlBase  = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_CALENDAR.self::CST_AMP.self::CST_SUBONGLET.'=';
        // Construction de la cellule
		if ($blnMonday) {
			$aHref = $urlBase.self::CST_CAL_WEEK.self::CST_AMP.self::CST_CAL_CURDAY.'='.date('m-d-Y', $tsDisplay);
			$aClass = 'fc-daygrid-day-number text-white float-left';
			$aAttributes = array(
				self::ATTR_STYLE => 'position: absolute; left: 0;',
			);
			$strWeekLink = $this->getLink(date('W', $tsDisplay), $aHref, $aClass, $aAttributes);
		} else {
			$strWeekLink = '';
		}
		
        $urlBase = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_CALENDAR.self::CST_AMP.self::CST_SUBONGLET.'=';
        $url = $urlBase.self::CST_CAL_DAY.self::CST_AMP.self::CST_CAL_CURDAY.'='.date('m-d-Y', $tsDisplay);
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