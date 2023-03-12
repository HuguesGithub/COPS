<?php
namespace core\bean;

use core\domain;
use core\services\WpCategoryServices;
use core\services\CopsIndexServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminBean
 * @author Hugues
 * @since 1.22.10.18
 * @version 1.22.10.18
 */
class WpPageAdminBean extends WpPageBean
{
    public $slugPage;
    public $slugOnglet;
    public $slugSubOnglet;
    
    public $arrSubOnglets = array();

    /**
     * Class Constructor
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function __construct()
    {
        $this->slugPage = self::PAGE_ADMIN;
        $this->slugOnglet = $this->initVar(self::CST_ONGLET);
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        
        $this->urlOnglet = $this->getPageUrl().'?'.self::CST_ONGLET.'=';
        $this->initServices();
        
        $this->analyzeUri();
//        $this->CopsPlayerServices = new CopsPlayerServices();
//        $this->CopsMailServices   = new CopsMailServices();
    
        if (isset($_POST[self::FIELD_MATRICULE])) {
            // On cherche a priori à se logguer
            $attributes[self::SQL_WHERE_FILTERS] = array(
                self::FIELD_ID => self::SQL_JOKER_SEARCH,
                self::FIELD_MATRICULE => $_POST[self::FIELD_MATRICULE],
                self::FIELD_PASSWORD  => ($_POST[self::FIELD_PASSWORD]=='' ? '' : md5($_POST[self::FIELD_PASSWORD])),
            );
//            $objsCopsPlayer = $this->CopsPlayerServices->getCopsPlayers($attributes);
//            if (!empty($objsCopsPlayer)) {
//                $this->CopsPlayer = array_shift($objsCopsPlayer);
                $_SESSION[self::FIELD_MATRICULE] = $_POST[self::FIELD_MATRICULE];
//            } else {
//                $_SESSION[self::FIELD_MATRICULE] = 'err_login';
//            }
        } elseif (isset($_GET['logout'])) {
            // On cherche a priori à se déconnecter
            unset($_SESSION[self::FIELD_MATRICULE]);
        } elseif (isset($_SESSION[self::FIELD_MATRICULE])) {
//            $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
        }
        
        $this->arrSidebarContent = array(
            self::ONGLET_DESK => array(
                self::FIELD_ICON  => 'desktop',
                self::FIELD_LABEL => 'Bureau',
            ),
            self::ONGLET_LIBRARY => array(
                self::FIELD_ICON  => 'book',
                self::FIELD_LABEL => 'Bibliothèque',
            ),
        );
    if (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='Guest') {
            $this->arrSidebarContentNonGuest = array(
                self::ONGLET_INBOX => array(
                    self::FIELD_ICON  => 'envelope',
                    self::FIELD_LABEL => 'Messagerie',
                ),
                self::ONGLET_CALENDAR => array(
                    self::FIELD_ICON   => 'calendar-days',
                    self::FIELD_LABEL  => 'Calendrier',
                    /*
                    self::CST_CHILDREN => array(
                        self::CST_CAL_MONTH  => 'Calendrier',
                        self::CST_CAL_EVENT  => 'Événements',
                        self::CST_CAL_PARAM  => 'Paramètres',
                    ),
                    */
                ),
            );
            $this->arrSidebarContent = array_merge($this->arrSidebarContent, $this->arrSidebarContentNonGuest);
            /*
            $this->arrSidebarContent = array(
                self::ONGLET_AUTOPSIE => array(
                    self::FIELD_ICON  => 'box-archive',
                    self::FIELD_LABEL => 'Autopsies',
                ),
                self::ONGLET_ENQUETE => array(
                    self::FIELD_ICON  => self::I_FILE_CATEGORY,
                    self::FIELD_LABEL => 'Enquêtes',
                ),
                
                self::ONGLET_ARCHIVE => array(
                    self::FIELD_ICON  => 'box-archive',
                    self::FIELD_LABEL => 'Archives',
                ),
                'player' => array(
                    self::FIELD_ICON   => 'user',
                    self::FIELD_LABEL  => 'Personnage',
                    self::CST_CHILDREN => array(
                        'player-carac'  => 'Caractéristiques',
                        'player-comps'  => 'Compétences',
                        'player-story'  => 'Background',
                    ),
                ),
            );
            */
        }

        $this->btnDark = 'btn-dark';
        $this->btnDisabled = 'btn-dark disabled';
        
        // Le lien vers la Home
        $aContent = $this->getIcon('desktop');
        $buttonContent = $this->getLink($aContent, '/'.self::PAGE_ADMIN, self::CST_TEXT_WHITE);
        if ($this->slugOnglet=='desk' || $this->slugOnglet=='') {
            $buttonAttributes = array(self::ATTR_CLASS=>$this->btnDisabled);
        } else {
            $buttonAttributes = array(self::ATTR_CLASS=>$this->btnDark);
        }
        $this->breadCrumbsContent = $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
        $this->strTitle = '';
    }

    private function initServices()
    {
        // On initialise les services
        $this->objWpCategoryServices = new WpCategoryServices();
        $this->objCopsIndexServices  = new CopsIndexServices();
    }

    /**
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function analyzeUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri, '?');
        if ($pos!==false) {
            $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
            if (!empty($arrParams)) {
                foreach ($arrParams as $param) {
                    list($key, $value) = explode('=', $param);
                    $this->urlParams[$key] = $value;
                }
            }
            $uri = substr($uri, 0, $pos-1);
        }
        $pos = strpos($uri, '#');
        if ($pos!==false) {
            $this->anchor = substr($uri, $pos+1, strlen($uri));
        }
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->urlParams[$key] = $value;
            }
        }
        return $uri;
    }

    /**
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.19
     */
    public function getContentPage()
    {
        if (!self::isCopsLogged()) {
            // Soit on n'est pas loggué et on affiche la mire d'identification.
            // Celle-ci est invisible et passe visible en cas de souris qui bouge ou touche cliquée.
            $urlTemplate = self::WEB_PPFS_CONNEX_PANEL;
            if (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]=='err_login') {
                $strNotification  = "Une erreur est survenue lors de la saisie de votre identifiant et de votre ";
                $strNotification .= "mot de passe.<br>L'un des champs était vide, ou les deux ne correspondaient";
                $strNotification .= " pas à une valeur attendue.<br>Veuillez réessayer ou contacter un ";
                $strNotification .=  "administrateur.<br><br>";
                unset($_SESSION[self::FIELD_MATRICULE]);
            } else {
                $strNotification = '';
            }
            $attributes = array(
                ($strNotification=='' ? 'd-none' : ''),
                $strNotification,
            );
            return $this->getRender($urlTemplate, $attributes);
        }
        try {
            if (!isset($this->urlParams[self::CST_ONGLET])) {
                $this->urlParams[self::CST_ONGLET] = '';
             }
                switch ($this->urlParams[self::CST_ONGLET]) {
                case self::ONGLET_CALENDAR :
                    $objBean = WpPageAdminCalendarBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_INBOX :
                    $objBean = WpPageAdminMailBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_LIBRARY :
                    $objBean = WpPageAdminLibraryBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case 'player' :
                    $objBean = new AdminCopsPlayerPageBean();
                    break;
                case self::ONGLET_PROFILE :
                    $objBean = new AdminCopsProfilePageBean();
                    break;
                case self::ONGLET_ENQUETE :
                    $objBean = new WpPageAdminEnqueteBean();
                    break;
                case self::ONGLET_AUTOPSIE :
                    $objBean = new WpPageAdminAutopsieBean();
                    break;
                case self::ONGLET_DESK   :
                default       :
                    $objBean = $this;
                break;
            }
            $returned = $objBean->getBoard();
        } catch (\Exception $Exception) {
            $returned = 'Error';
        }
        return $returned;
    }

    /**
     * Retourne le contenu de l'interface
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function getBoard()
    {
        // Soit on est loggué et on affiche le contenu du bureau du cops
        $urlTemplate = self::WEB_PP_BOARD;
        $attributes = array(
            // La sidebar
            $this->getSideBar(),
            // Le contenu de la page
            $this->getOngletContent(),
            // L'id
            '',//$this->CopsPlayer->getMaskMatricule(),
            // Le nom
            '',//$this->CopsPlayer->getFullName(),
            // La barre de navigation
            $this->getNavigationBar(),
            // Header
            $this->getContentHeader(),
            // Version
            self::VERSION,
            '', '', '', '', '', '', '', '', '', '', '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
     protected function getSideBar()
     {
         $urlTemplate = self::WEB_PPF_SIDEBAR;
         
         $sidebarContent = '';
         if (!isset($this->urlParams[self::CST_ONGLET])) {
            $this->urlParams[self::CST_ONGLET] = '';
         }
         foreach ($this->arrSidebarContent as $strOnglet => $arrOnglet) {
            $curOnglet = ($strOnglet==$this->urlParams[self::CST_ONGLET]);
            $hasChildren = isset($arrOnglet[self::CST_CHILDREN]);
         
            // Construction du label
            $pContent  = $arrOnglet[self::FIELD_LABEL];
            $pContent .= ($hasChildren ? $this->getIcon(self::I_ANGLE_LEFT, 'right') : '');
         
            // Construction du lien
            $aContent  = $this->getIcon($arrOnglet[self::FIELD_ICON], 'nav-icon');
            $aContent .= $this->getBalise(self::TAG_P, $pContent);
            $aAttributes = array(
                self::ATTR_HREF  => $this->urlOnglet.$strOnglet,
                self::ATTR_CLASS => 'nav-link'.($curOnglet ? ' '.self::CST_ACTIVE : ''),
            );
            $superLiContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
         
            // S'il a des enfants, on enrichit
            if ($hasChildren) {
                $ulContent = '';
                foreach ($arrOnglet[self::CST_CHILDREN] as $strSubOnglet => $label) {
                    if ($strSubOnglet==$this->urlParams[self::CST_SUBONGLET] ||
                        $this->urlParams[self::CST_SUBONGLET]=='date' && $strSubOnglet=='allDates') {
                        $extraClass = ' '.self::CST_ACTIVE;
                    } else {
                        $extraClass = '';
                    }
                    $aContent  = $this->getIcon(self::I_CIRCLE, 'nav-icon').$this->getBalise(self::TAG_P, $label);
                    $aAttributes = array(
                        self::ATTR_HREF  => $this->urlOnglet.$strOnglet.'&amp;subOnglet='.$strSubOnglet,
                        self::ATTR_CLASS => 'nav-link'.$extraClass,
                    );
                    $liContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
                    $ulContent .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>'nav-item'));
                }
                $liAttributes = array(self::ATTR_CLASS=>'nav nav-treeview');
                $superLiContent .= $this->getBalise(self::TAG_UL, $ulContent, $liAttributes);
            }
         
            // Construction de l'élément de la liste
            $liAttributes = array(self::ATTR_CLASS=>'nav-item'.($curOnglet ? ' menu-open' : ''));
            $sidebarContent .= $this->getBalise(self::TAG_LI, $superLiContent, $liAttributes);
         }
         
         $attributes = array(
            $sidebarContent,
            // La date
            self::getCopsDate('D m-d-Y'),
            // L'heure
            self::getCopsDate('H:i:s'),
         );
         return $this->getRender($urlTemplate, $attributes);
     }

     /**
      * @return string
      * @since v1.22.11.11
      * @version v1.22.11.11
      */
     public function getOngletContent()
     { return ''; }
     
    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function getNavigationBar()
    {
        // On détermine le nombre de messages non lus dans la boite de réception
        $nbMailsNonLus = 0;
//        $nbMailsNonLus = $this->CopsMailServices->getNombreMailsNonLus();

        $urlTemplate = self::WEB_PPFS_CONTENT_NAVBAR;
        $attributes = array(
            // Nom Prénom de la personne logguée
            '',//$this->CopsPlayer->getFullName(),
            // Si présence de notifications, le badge
            // <span class="badge badge-warning navbar-badge">0</span>
            '',
            // La liste des notifications
            // Ou un message adapté s'il n'y en a pas.
            '<span class="dropdown-item dropdown-header">Aucune nouvelle Notification</span>',
            // Si présence d'un nouveau mail, le badge
            ($nbMailsNonLus!=0 ? '<span class="badge badge-success navbar-badge">'.$nbMailsNonLus.'</span>' : ''),
            // Si Guest, on cache des trucs.
            ($_SESSION[self::FIELD_MATRICULE]=='Guest' ? ' style="display:none !important;"' : ''),
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function getContentHeader()
    {
        $urlTemplate = self::WEB_PPFS_CONTENT_HEADER;
        $attributes = array(
            // Le Titre
            $this->strTitle,
            // Le BreadCrumb
            $this->getDiv($this->breadCrumbsContent, array(self::ATTR_CLASS=>'btn-group float-sm-right')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
  
    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     *
    public function getFolderBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-li-menu-folder.php';
        /////////////////////////////////////////
        // Construction du panneau de gauche
        $strLeftPanel = '';
        foreach ($this->arrSubOnglets as $slug => $subOnglet) {
            // On exclu les sous onglets sans icones
            if (!isset($subOnglet[self::FIELD_ICON])) {
                continue;
            }
            // On construit l'entrée de l'onglet/
            $attributes = array(
                // Menu sélectionné ou pas ?
                ($slug==$this->slugSubOnglet ? ' '.self::CST_ACTIVE : ''),
                // L'url du folder
                $this->getSubOngletUrl($slug),
                // L'icône
                $subOnglet[self::FIELD_ICON],
                // Le libellé
                $subOnglet[self::FIELD_LABEL],
            );
            $strLeftPanel .= $this->getRender($urlTemplate, $attributes);
        }
        /////////////////////////////////////////
        return $strLeftPanel;
    }
        
    /**
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getPageUrl()
    { return '/'.$this->slugPage; }

    /**
     * @param array $urlElements
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getOngletUrl($urlElements=array())
    {
        $url = $this->getPageUrl().'?'.self::CST_ONGLET.'='.$this->slugOnglet;
        if (isset($urlElements[self::CST_SUBONGLET])) {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
            unset($urlElements[self::CST_SUBONGLET]);
        }
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= self::CST_AMP.$key.'='.$value;
                }
            }
        }
        return $url;
    }
    
    /**
     * @param array
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getUrl($urlElements=array())
    {
        $url = $this->getPageUrl();
        /////////////////////////////////////////////
        // Si l'onglet est passé en paramètre et qu'il est défini, on va le reprendre
        // S'il est défini et vide, on va l'enlever.
        // S'il n'est pas défini, on va mettre l'onglet courant par défaut.
        if (!isset($urlElements[self::CST_ONGLET])) {
            $url .= '?'.self::CST_ONGLET.'='.$this->slugOnglet;
        } else {
            $url .= '?'.self::CST_ONGLET.'='.$urlElements[self::CST_ONGLET];
            unset($urlElements[self::CST_ONGLET]);
        }
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // On fait de même avec le subOnglet
        if (!isset($urlElements[self::CST_SUBONGLET])) {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$this->slugSubOnglet;
        } else {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
            unset($urlElements[self::CST_SUBONGLET]);
        }
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Maintenant, on doit ajouter ceux passés en paramètre
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= self::CST_AMP.$key.'='.$value;
                }
            }
        }
        /////////////////////////////////////////////
        
        return $url;
    }

    /**
     * @param array $objs
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function buildPagination(&$objs)
    {
        $nbItems = count($objs);
        $nbItemsPerPage = 10;
        $nbPages = ceil($nbItems/$nbItemsPerPage);
        $strPagination = '';
        $arrUrl = array();
        if ($this->catSlug!='') {
            $arrUrl[self::CST_CAT_SLUG] = $this->catSlug;
        }

        if ($nbPages>1) {
            $this->blnHasPagination = true;
            // Le bouton page précédente
            $label = $this->getIcon(self::I_CARET_LEFT);
            if ($this->curPage!=1) {
                $btnClass = '';
                $arrUrl[self::CST_CURPAGE] = $this->curPage-1;
                $href = $this->getUrl($arrUrl);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= $this->getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, array($firstItem+1, $lastItem, $nbItems));
            
            // Le bouton page suivante
            $label = $this->getIcon(self::I_CARET_RIGHT);
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $arrUrl[self::CST_CURPAGE] = $this->curPage+1;
                $href = $this->getUrl($arrUrl);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= self::CST_NBSP.$this->getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }
    
}
