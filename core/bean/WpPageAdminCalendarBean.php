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
        );
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDark));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
        
        /*
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = array(
            self::CST_CAL_EVENT => array(self::FIELD_LABEL  => 'Événements'),
            self::CST_CAL_PARAM => array(self::FIELD_LABEL  => 'Paramètres'),
        );
        */
    }
    
    /**
     * @since v1.22.11.21
     * @version v1.22.11.21
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        switch ($slugSubContent) {
            case self::CST_CAL_EVENT :
                // TODO
                //$objBean = new WpPageAdminCalendarEventBean();
                break;
            case self::CST_CAL_PARAM :
                // TODO
                //$objBean = new WpPageAdminCalendarParamBean();
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
            $href = $this->getUrl(array(self::CST_SUBONGLET => $key));
            $liContent = $this->getLink($aContent, $href, 'nav-link text-white');
            
            // Si le slug affiché vaut celui du menu ou qu'on est sur la vue par défaut est le menu est inbox
            $blnActive = ($this->slugSubOnglet==$key || $this->slugSubOnglet=='' && $key==self::CST_CAL_MONTH);
            $strLiClass = 'nav-item'.($blnActive ? ' '.self::CST_ACTIVE : '');
            $menuContent .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>$strLiClass));
        }
        /////////////////////////////////////////
        return $menuContent;
    }
}
