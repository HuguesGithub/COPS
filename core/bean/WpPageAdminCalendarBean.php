<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarBean
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.21
 */
class WpPageAdminCalendarBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        /////////////////////////////////////////
        // Définition des services
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_CALENDAR;
        $this->titreOnglet = 'Calendrier';
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        
        // On récupère la date du jour
        if (isset($this->urlParams[self::CST_CAL_CURDAY])) {
            $this->curStrDate = $this->urlParams[self::CST_CAL_CURDAY];
        } else {
            $this->curStrDate = self::getCopsDate('m-d-Y');
        }
        
        /////////////////////////////////////////
        // Construction du menu
        $extraUrl = self::CST_CAL_CURDAY.'='.$this->curStrDate;
        $this->arrMenu = array(
            self::CST_CAL_MONTH => array(self::FIELD_LABEL => 'Mensuel', self::CST_URL => $extraUrl),
            self::CST_CAL_WEEK => array(self::FIELD_LABEL => 'Hebdomadaire', self::CST_URL => $extraUrl),
            self::CST_CAL_DAY => array(self::FIELD_LABEL  => 'Quotidien', self::CST_URL => $extraUrl),
            self::CST_CAL_EVENT => array(self::FIELD_LABEL  => 'Événements'),
        );
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDark));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        switch ($slugSubContent) {
            case self::CST_CAL_EVENT :
                $objBean = new WpPageAdminCalendarEventBean();
                break;
            case self::CST_CAL_DAY :
                $objBean = new WpPageAdminCalendarDayBean();
                break;
            case self::CST_CAL_WEEK :
                $objBean = new WpPageAdminCalendarWeekBean();
                break;
            case self::CST_CAL_MONTH :
            default :
                $objBean = new WpPageAdminCalendarMonthBean();
                break;
        }
        return $objBean;
    }
    
    /**
     * @since 1.22.11.21
     * @version 1.22.11.21
     */
    public function getFcDayClass($tsDisplay)
    {
        // On récupère le timestamp du jour COPS courant
        $tsToday = self::getCopsDate('tsStart');

        ///////////////////////////////////////////////////
        // On construit la classe de la cellule avec le jour de la semaine
        $strClass = 'fc-day-'.strtolower(date('D', $tsDisplay));
        // La date passée, présente ou future
        // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
        if ($tsDisplay==$tsToday) {
            $strClass .= ' fc-day-today';
        } elseif ($tsDisplay<=$tsToday) {
            $strClass .= ' fc-day-past';
        } else {
            $strClass .= ' fc-day-future';
        }
        // Un autre mois ou non
        // si le jour est dans un autre mois : fc-day-other
        if (date('m', $tsDisplay)!=date('m', $tsToday)) {
            $strClass .= ' fc-day-other';
        }
        ///////////////////////////////////////////////////
        return $strClass;
    }
    
    /**
     * @return string
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getMenuContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        $menuContent = '';
        foreach ($this->arrMenu as $key => $arrMenu) {
            //$aContent = $this->getIcon($arrMenu[self::FIELD_ICON]).self::CST_NBSP.$arrMenu[self::FIELD_LABEL];
            $aContent = $arrMenu[self::FIELD_LABEL];
			$urlElements = array(self::CST_SUBONGLET => $key);
			if (isset($arrMenu[self::CST_URL])) {
				list($k, $v) = explode('=', $arrMenu[self::CST_URL]);
				$urlElements[$k] = $v;
			}
            $href = $this->getUrl($urlElements);
            $liContent = $this->getLink($aContent, $href, 'nav-link text-white');
            
            // Si le slug affiché vaut celui du menu ou qu'on est sur la vue par défaut est le menu est inbox
            $blnActive = ($this->slugSubOnglet==$key || $this->slugSubOnglet=='' && $key==self::CST_CAL_MONTH);
            $strLiClass = 'nav-item'.($blnActive ? ' '.self::CST_ACTIVE : '');
            $menuContent .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>$strLiClass));
        }
        /////////////////////////////////////////
        return $menuContent;
    }
    
    /**
     * @return string
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getSectionCalendar($calendarHeader, $viewContent)
    {
        /////////////////////////////////////////
        // On récupère le jour courant
        list($m, $d, $y) = explode('-', $this->curStrDate);
        
        $urlElements = array(
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => $this->slugSubOnglet,
        );
        $urlToday = $this->getUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $this->prevCurday;
        $urlPrev  = $this->getUrl($urlElements);
        $urlElements[self::CST_CAL_CURDAY] = $this->nextCurday;
        $urlNext  = $this->getUrl($urlElements);
        
        $urlElements[self::CST_CAL_CURDAY] = $this->curStrDate;
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_MONTH;
        $urlMonth = $this->getUrl($urlElements);
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_WEEK;
        $urlWeek  = $this->getUrl($urlElements);
        $urlElements[self::CST_SUBONGLET] = self::CST_CAL_DAY;
        $urlDay   = $this->getUrl($urlElements);
        
        /////////////////////////////////////////
        $urlTemplate = self::PF_SECTION_CALENDAR;
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
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public function getColumnHoraire($h)
    {
        $hPadded = str_pad($h, 2, '0', STR_PAD_LEFT);
        $cushionAttributes = array(self::ATTR_CLASS=>'fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion');
        $shrinkCushion = $this->getDiv(date('ga', mktime($h, 0, 0)), $cushionAttributes);
        $frameAttributes = array(self::ATTR_CLASS=>'fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame');
        $shrinkFrame = $this->getDiv($shrinkCushion, $frameAttributes);
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink',
            'data-time' => $hPadded.':00:00',
        );
        $firstRow = $this->getBalise(self::TAG_TD, $shrinkFrame, $tdAttributes);
        
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane',
            'data-time' => $hPadded.':00:00',
        );
        $firstRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00',
        );
        $secondRow = $this->getBalise(self::TAG_TD, '', $tdAttributes);
        $tdAttributes = array(
            self::ATTR_CLASS => 'fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor',
            'data-time' => $hPadded.':30:00',
        );
        $secondRow .= $this->getBalise(self::TAG_TD, '', $tdAttributes);
        
        return $this->getBalise(self::TAG_TR, $firstRow).$this->getBalise(self::TAG_TR, $secondRow);
    }
}
