<?php
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
        $this->urlOnglet = '/'.$this->slugPage.'?'.self::CST_ONGLET.'=';
        
        $this->analyzeUri();
        $this->CopsPlayerServices = new CopsPlayerServices();
        $this->CopsMailServices   = new CopsMailServices();
    
        if (isset($_POST[self::FIELD_MATRICULE])) {
            // On cherche a priori à se logguer
            $attributes[self::SQL_WHERE_FILTERS] = array(
                self::FIELD_ID => self::SQL_JOKER_SEARCH,
                self::FIELD_MATRICULE => $_POST[self::FIELD_MATRICULE],
                self::FIELD_PASSWORD  => ($_POST[self::FIELD_PASSWORD]=='' ? '' : md5($_POST[self::FIELD_PASSWORD])),
            );
            $objsCopsPlayer = $this->CopsPlayerServices->getCopsPlayers($attributes);
            if (!empty($objsCopsPlayer)) {
                $this->CopsPlayer = array_shift($objsCopsPlayer);
                $_SESSION[self::FIELD_MATRICULE] = $_POST[self::FIELD_MATRICULE];
            } else {
                $_SESSION[self::FIELD_MATRICULE] = 'err_login';
            }
        } elseif (isset($_GET['logout'])) {
            // On cherche a priori à se déconnecter
            unset($_SESSION[self::FIELD_MATRICULE]);
        } elseif (isset($_SESSION[self::FIELD_MATRICULE])) {
            $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
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
        if ($_SESSION[self::FIELD_MATRICULE]!='Guest') {
            /*
            $this->arrSidebarContent = array(
                self::ONGLET_DESK => array(
                    self::FIELD_ICON  => 'desktop',
                    self::FIELD_LABEL => 'Bureau',
                ),
                self::ONGLET_AUTOPSIE => array(
                    self::FIELD_ICON  => 'box-archive',
                    self::FIELD_LABEL => 'Autopsies',
                ),
                self::ONGLET_ENQUETE => array(
                    self::FIELD_ICON  => self::I_FILE_CATEGORY,
                    self::FIELD_LABEL => 'Enquêtes',
                ),
                
                self::ONGLET_INBOX => array(
                    self::FIELD_ICON  => 'envelope',
                    self::FIELD_LABEL => 'Messagerie',
                ),
                self::ONGLET_CALENDAR => array(
                    self::FIELD_ICON   => 'calendar-days',
                    self::FIELD_LABEL  => 'Calendrier',
                    self::CST_CHILDREN => array(
                        self::CST_CAL_MONTH  => 'Calendrier',
                        self::CST_CAL_EVENT  => 'Événements',
                        self::CST_CAL_PARAM  => 'Paramètres',
                    ),
                ),
                self::ONGLET_ARCHIVE => array(
                    self::FIELD_ICON  => 'box-archive',
                    self::FIELD_LABEL => 'Archives',
                ),
                self::ONGLET_LIBRARY => array(
                    self::FIELD_ICON  => 'book',
                    self::FIELD_LABEL => 'Bibliothèque',
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
            $urlTemplate = 'web/pages/public/fragments/public-fragments-section-connexion-panel.php';
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
            switch ($this->urlParams[self::CST_ONGLET]) {
                case self::ONGLET_CALENDAR :
                    $objBean = AdminCopsCalendarPageBean::getCalendarBean($this->urlParams[self::CST_SUBONGLET]);
                    break;
                case self::ONGLET_INBOX :
                    $objBean = new WpPageAdminInboxBean();
                    break;
                case self::ONGLET_LIBRARY :
                    $objBean = new WpPageAdminLibraryBean();
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
            $objBean->initBoard();
            $returned = $objBean->getBoard();
        } catch (\Exception $Exception) {
            $returned = 'Error';
        }
        return $returned;
    }
    
    public function initBoard()
    {
        $this->buildBreadCrumbs('Bureau');
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
        $urlTemplate = 'web/pages/public/public-board.php';
        $attributes = array(
            // La sidebar
            $this->getSideBar(),
            // Le contenu de la page
            $this->getOngletContent(),
            // L'id
            $this->CopsPlayer->getMaskMatricule(),
            // Le nom
            $this->CopsPlayer->getFullName(),
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
    
    public function getOngletContent()
    { return ''; }

    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function buildBreadCrumbs($label, $slug=null, $hasDropdown=false)
    {
        // Le lien vers la Home
        $aContent = $this->getIcon('desktop');
        $aAttributes = array(self::ATTR_HREF=>'/admin/', self::ATTR_CLASS=>'text-white');
        $buttonContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
        $buttonAttributes = array('type'=>self::TAG_BUTTON, self::ATTR_CLASS=>'btn btn-sm btn-dark');
        $breadCrumbsContent = $this->getBalise(self::TAG_BUTTON, $buttonContent, $buttonAttributes);

        // Le lien intermédiaire ou final si slug vaut null
        if ($slug==null) {
            $buttonAttributes = array('type'=>self::TAG_BUTTON, self::ATTR_CLASS=>'btn btn-sm btn-dark disabled');
            $breadCrumbsContent .= $this->getBalise(self::TAG_BUTTON, $label, $buttonAttributes);
        } else {
            $aAttributes = array(self::ATTR_HREF=>$this->urlOnglet.$slug, self::ATTR_CLASS=>'text-white');
            $buttonContent = $this->getBalise(self::TAG_A, $label, $aAttributes);
            $buttonAttributes = array('type'=>self::TAG_BUTTON, self::ATTR_CLASS=>'btn btn-sm btn-dark');
            $breadCrumbsContent .= $this->getBalise(self::TAG_BUTTON, $buttonContent, $buttonAttributes);

            // Le lien ou le dropdown selon le type de mixed
            if ($hasDropdown===true) {
                $breadCrumbsContent .= '<div class="btn-group">';
                $buttonContent = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
                $buttonAttributes = array(
                    'type'           => self::TAG_BUTTON,
                    self::ATTR_CLASS => 'btn btn-sm btn-dark dropdown-toggle',
                    'data-toggle'    => 'dropdown',
                );
                $breadCrumbsContent .= $this->getBalise(self::TAG_BUTTON, $buttonContent, $buttonAttributes);
                $breadCrumbsContent .= '<div class="dropdown-menu">';
                foreach ($this->arrSubOnglets as $subOnglet => $arrData) {
                    if ($arrData[self::FIELD_LABEL]=='' || !isset($arrData[self::FIELD_ICON])) {
                        continue;
                    }
                    $url  = $this->urlOnglet.$slug.'&subOnglet='.$subOnglet;
                    $url .= (isset($arrData['url']) ? '&'.$arrData['url'] : '');
                    $breadCrumbsContent .= '<a class="dropdown-item btn-sm" href="'.$url.'">';
                    $breadCrumbsContent .= $arrData[self::FIELD_LABEL].'</a>';
                }
                $breadCrumbsContent .= '</div>';
                $breadCrumbsContent .= '</div>';
            } elseif ($hasDropdown!==false) {
                $buttonAttributes = array('type'=>self::TAG_BUTTON, self::ATTR_CLASS=>'btn btn-sm btn-dark disabled');
                $breadCrumbsContent .= $this->getBalise(self::TAG_BUTTON, $hasDropdown, $buttonAttributes);
            }
        }
        $divAttributes = array(self::ATTR_CLASS=>'btn-group float-sm-right');
        $this->breadCrumbs = $this->getBalise(self::TAG_DIV, $breadCrumbsContent, $divAttributes);
    }

    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    protected function getSideBar()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-sidebar.php';

        $sidebarContent = '';
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
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function getNavigationBar()
    {
        // On détermine le nombre de messages non lus dans la boite de réception
        $nbMailsNonLus = $this->CopsMailServices->getNombreMailsNonLus();

        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-content-navigation-bar.php';
        $attributes = array(
            // Nom Prénom de la personne logguée
            $this->CopsPlayer->getFullName(),
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
    public function getNotificationsDropdown()
    {
    /*
    $mocks_notifs = array(
      1 => array(1, 3, 1, '2022-03-01 20:00:00', 0),
      2 => array(2, 3, 1, '2022-03-03 20:00:00', 0),
      3 => array(3, 3, 1, '2022-03-08 20:00:00', 0),
    );

    $mocks_type_notif = array(
      1 => array(1, '%s événement%s à venir', 'calendar-alt'),
    );
    //////////////////////////////////////////////////////////////////
    // Construction de la liste des notifications
    $squelette = '  <div class="dropdown-divider"></div><a href="%s" class="dropdown-item"><i class="fas fa-%s mr-2"></i> %s<span class="float-right text-muted text-sm">%s</span></a><!-- ./ dropdown-item -->';
    $strNotifications = '';
    $nbNotifications  = 0;

    // Build From MOCKS
    if ($this->GoneJoueur->getId()==3) {
      $nbNotifications  = count($mocks_notifs);
      $strNbNotifications = $nbNotifications.' Notification'.($nbNotifications<=1 ? '' : 's');
      $d = DateTime::createFromFormat('Y-m-d H:i:s', $mocks_notifs[3][3], new DateTimeZone('UTC'));
      $spanTs = time()-$d->getTimestamp();
      if ($spanTs>=86400) {
        $nb = floor($spanTs/86400);
        $strDelay = $nb.' jour'.($nb>1?'s':'');
      } elseif ($spanTs>=3600) {
        $nb = floor($spanTs/3600);
        $strDelay = $nb.' heure'.($nb>1?'s':'');
      } elseif ($spanTs>=60) {
        $nb = floor($spanTs/60);
        $strDelay = $nb.' minutes'.($nb>1?'s':'');
      } else {
        $strDelay = 'à l\'instant';
      }
      $strNotifications = sprintf($squelette, '#', $mocks_type_notif[1][2], sprintf($mocks_type_notif[1][1], $nbNotifications, ($nbNotifications>1?'s':'')), $strDelay);
    } else {
      $nbNotifications  = 0;
      $strNbNotifications = 'Aucune nouvelle Notification';
      $strNotifications = '';
    }

    //////////////////////////////////////////////////////////////////

    $urlTemplate = 'web/pages/admin/fragments/admin-dropdown-notifications.php';
    $attributes = array(
      // Le nombre de notifications
      $nbNotifications,
      // La phrase qui va bien
      $strNbNotifications,
      // La liste des Notifications
      $strNotifications,
      // L'url pour toutes les voir
      '#',
      // On affiche ou non le badge selon le nombre de nouvelles notifications
      ($nbNotifications==0 ? 'hidden' : ''),
      '', '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);


    $strNotificationsDropdown  = '<li class="nav-item dropdown">';
    $strNotificationsDropdown .= $this->getRender($urlTemplate, $attributes);
    $strNotificationsDropdown .= '</li>';
*/
        $strNotificationsDropdown = '';
        return $strNotificationsDropdown;

    /*
  <div class="dropdown-divider"></div>
  <a href="#" class="dropdown-item">
    <i class="fas fa-envelope mr-2"></i> 4 new messages
    <span class="float-right text-muted text-sm">3 mins</span>
  </a>
  <!-- ./ dropdown-item -->
  <a href="#" class="dropdown-item">
    <i class="fas fa-users mr-2"></i> 8 friend requests
    <span class="float-right text-muted text-sm">12 hours</span>
  </a>
  <div class="dropdown-divider"></div>
  <a href="#" class="dropdown-item">
    <i class="fas fa-file mr-2"></i> 3 new reports
    <span class="float-right text-muted text-sm">2 days</span>
  </a>
        </div>
    */
    }

    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function getContentHeader()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-content-header.php';
        $attributes = array(
            // Le Titre
            $this->strTitle,
            // Le BreadCrumb
            $this->breadCrumbs,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
  
    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
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
    
    public function getSubOngletUrl($slugSubOnglet='')
    {
        if ($slugSubOnglet=='') {
            $slugSubOnglet = $this->slugSubOnglet;
        }
        return $this->getOngletUrl().'&amp;'.self::CST_SUBONGLET.'='.$slugSubOnglet;
    }
    
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
            $url .= '&amp;'.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
        }
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= '&amp;'.$key.'='.$value;
                }
            }
        }
        return $url;
    }
    
    /**
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getPageUrl()
    { return '/'.$this->slugPage; }

    /**
     * @param array
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getUrl($urlElements=array())
    {
        $url = '/'.$this->slugPage;
        /////////////////////////////////////////////
        // Si l'onglet est passé en paramètre et qu'il est défini, on va le reprendre
        // S'il est défini et vide, on va l'enlever.
        // S'il n'est pas défini, on va mettre l'onglet courant par défaut.
        if (!isset($urlElements[self::CST_ONGLET])) {
            $urlElements[self::CST_ONGLET] = $this->slugOnglet;
        }
        if ($urlElements[self::CST_ONGLET]!='') {
           $url .= '?'.self::CST_ONGLET.'='.$urlElements[self::CST_ONGLET];
        }
        unset($urlElements[self::CST_ONGLET]);
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // On fait de même avec le subOnglet
        if (!isset($urlElements[self::CST_SUBONGLET])) {
            $urlElements[self::CST_SUBONGLET] = $this->slugSubOnglet;
        }
        if ($urlElements[self::CST_SUBONGLET]!='') {
           $url .= '&amp;'.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
        }
        unset($urlElements[self::CST_SUBONGLET]);
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Maintenant, on doit ajouter ceux passer en paramètre
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= '&amp;'.$key.'='.$value;
                }
            }
        }
        /////////////////////////////////////////////
        
        return $url;
    }
    
}
