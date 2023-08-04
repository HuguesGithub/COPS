<?php
namespace core\bean;

use core\domain\CopsPlayerClass;
use core\services\WpCategoryServices;
use core\services\CopsIndexServices;
use core\services\CopsPlayerServices;
use core\services\CopsTchatServices;
use core\utils\DateUtils;
use core\utils\DiceUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminBean
 * @author Hugues
 * @since 1.22.10.18
 * @version v1.23.08.05
 */
class WpPageAdminBean extends WpPageBean
{
    public $objCopsPlayer;
    public $breadCrumbsContent = '';

    public $slugPage;
    public $slugOnglet;
    public $slugSubOnglet;
    
    public $arrSubOnglets = [];
    public $urlAttributes = [];

    /**
     * Class Constructor
     * @since 1.22.10.18
     * @version v1.23.06.25
     */
    public function __construct()
    {
        $this->initLogIn();
        $this->initSidebar();

        $this->slugPage = self::PAGE_ADMIN;
        $this->slugOnglet = $this->initVar(self::CST_ONGLET);
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->urlAttributes = [self::WP_PAGE=>$this->slugPage];

        // Le lien vers la Home
        $aContent = HtmlUtils::getIcon(self::I_DESKTOP);
        $buttonContent = HtmlUtils::getLink(
            $aContent,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        if ($this->slugOnglet==self::ONGLET_DESK || $this->slugOnglet=='') {
            $buttonAttributes = [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK_DISABLED];
        } else {
            $buttonAttributes = [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK];
        }
        $this->breadCrumbsContent = HtmlUtils::getButton($buttonContent, $buttonAttributes);
    }

    /**
     * @since v1.23.06.19
     * @version v1.23.07.02
     */
    public function initSidebar(): void
    {
        $this->arrSidebarContent = [
            self::ONGLET_DESK => [
                self::FIELD_ICON  => self::I_DESKTOP,
                self::FIELD_LABEL => self::LABEL_BUREAU,
            ],
//            self::ONGLET_LIBRARY => [self::FIELD_ICON  => 'book', self::FIELD_LABEL => self::LABEL_LIBRARY]
        ];
/*
            self::ONGLET_USERS => [
                self::FIELD_ICON  => self::I_USERS,
                self::FIELD_LABEL => 'COPS',
            ],
    if (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='Guest') {
            $this->arrSidebarContentNonGuest = [
                self::ONGLET_INBOX => [self::FIELD_ICON  => 'envelope', self::FIELD_LABEL => self::LABEL_MESSAGERIE],
                self::ONGLET_CALENDAR => [self::FIELD_ICON   => 'calendar-days', self::FIELD_LABEL  => 'Calendrier']
            ];
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
                        'player-carac'  => self::LABEL_ABILITIES,
                        'player-comps'  => self::LABEL_SKILLS,
                        'player-story'  => self::LABEL_BACKGROUND,
                    ),
                ),
            );
            * /
        }
*/
    }

    /**
     * @since v1.23.06.19
     * @version v1.23.06.25
     */
    public function getContentHeader(): string
    {
        $urlTemplate = self::WEB_PPFS_CONTENT_HEADER;
        $attributes = [
            // Le Titre
            $this->strTitle,
            // Le BreadCrumb
            HtmlUtils::getDiv($this->breadCrumbsContent, [self::ATTR_CLASS=>'btn-group float-sm-right']),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.19
     * @version v1.23.06.25
     */
    public function initLogIn(): void
    {
        // Récupération des données éventuelles relatives à l'identification
        $strMatricule = SessionUtils::fromPost(self::FIELD_MATRICULE);
        $password = SessionUtils::fromPost(self::FIELD_PASSWORD);
        $objCopsPlayerServices = new CopsPlayerServices();

        if ($strMatricule!='') {
            // On cherche a priori à se logguer
            $mdp = ($password=='' ? '' : md5((string) $password));
            $attributes[self::SQL_WHERE_FILTERS] = [
                self::FIELD_MATRICULE => $strMatricule,
                self::FIELD_PASSWORD  => $mdp
            ];
            // On requête pour trouver le compte associé.
            $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
            if (!empty($objsCopsPlayer)) {
                $this->objCopsPlayer = array_shift(($objsCopsPlayer));
                SessionUtils::setSession(self::FIELD_MATRICULE, $strMatricule);
            } else {
                $this->objCopsPlayer = new CopsPlayerClass();
            }
        } elseif (SessionUtils::fromGet('logout')=='logout') {
            // On cherche a priori à se déconnecter
            SessionUtils::unsetSession(self::FIELD_MATRICULE);
        } else {
            $attributes[self::SQL_WHERE_FILTERS] = [
                self::FIELD_MATRICULE => $_SESSION[self::FIELD_MATRICULE],
            ];
            $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
            if (!empty($objsCopsPlayer)) {
                $this->objCopsPlayer = array_shift(($objsCopsPlayer));
            } else {
                $this->objCopsPlayer = new CopsPlayerClass();
            }
        }
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.07.02
     */
    protected function getSideBar(): string
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
            $pContent .= ($hasChildren ? HtmlUtils::getIcon(self::I_ANGLE_LEFT, 'right') : '');
        
            // Construction du lien
            $aContent  = HtmlUtils::getIcon($arrOnglet[self::FIELD_ICON], 'nav-icon');
            $aContent .= $this->getBalise(self::TAG_P, $pContent);
            $strClasse = 'nav-link'.($curOnglet ? ' '.self::CST_ACTIVE : '');
            unset($this->urlAttributes[self::CST_SUBONGLET]);
            $urlAttributes = array_merge($this->urlAttributes, [self::CST_ONGLET => $strOnglet]);
            $superLiContent = HtmlUtils::getLink($aContent, UrlUtils::getPublicUrl($urlAttributes), $strClasse);
        
            // S'il a des enfants, on enrichit
            if ($hasChildren) {
                $ulContent = $this->getSideBarChildren($arrOnglet, $strOnglet);
                $liAttributes = [self::ATTR_CLASS=>'nav nav-treeview'];
                $superLiContent .= $this->getBalise(self::TAG_UL, $ulContent, $liAttributes);
            }
        
            // Construction de l'élément de la liste
            $liAttributes = [self::ATTR_CLASS=>'nav-item'.($curOnglet ? ' menu-open' : '')];
            $sidebarContent .= $this->getBalise(self::TAG_LI, $superLiContent, $liAttributes);
        }
        
        $attributes = [
            $sidebarContent,
            // La date
            DateUtils::getCopsDate(self::FORMAT_DATE_DMDY),
            // L'heure
            DateUtils::getCopsDate(self::FORMAT_DATE_HIS),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.18
     * @version v1.23.08.05
     */
    public function getNavigationBar()
    {
        $urlTemplate = self::WEB_PPFS_CONTENT_NAVBAR;

        $strLis = '';
        if ($this->objCopsPlayer->getField(self::FIELD_ID)!=64) {
            // Si on est identifié, mais pas Guest...
            $objTchatServices = new CopsTchatServices();
            $objsTchat = $objTchatServices->getTchats([], 'now');

            // On peut accéder au Tchat et être prévenu s'il y a de nouveaux messages
            $aContent = HtmlUtils::getIcon('comment');
            if (!empty($objsTchat)) {
                $aAttributes = [self::ATTR_CLASS => 'badge badge-warning navbar-badge'];
                $aContent .= HtmlUtils::getBalise(self::TAG_SPAN, count($objsTchat), $aAttributes);
            }
            $url = UrlUtils::getPublicUrl([self::WP_PAGE=>self::PAGE_ADMIN, self::CST_ONGLET=>self::ONGLET_TCHAT]);
            $liContent = HtmlUtils::getLink($aContent, $url, self::NAV_LINK);
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>self::NAV_ITEM]);

            // On peut accéder au profil du personnage
            $aContent = HtmlUtils::getIcon('user');
            $url = UrlUtils::getPublicUrl([self::WP_PAGE=>self::PAGE_ADMIN, self::CST_ONGLET=>self::ONGLET_PROFILE]);
            $liContent = HtmlUtils::getLink($aContent, $url, self::NAV_LINK);
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>self::NAV_ITEM]);
        }
        $aContent = HtmlUtils::getIcon('right-from-bracket');
        $url = UrlUtils::getPublicUrl([self::WP_PAGE=>self::PAGE_ADMIN, 'logout'=>'logout']);
        $liContent = HtmlUtils::getLink($aContent, $url, self::NAV_LINK);
        $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>self::NAV_ITEM]);

        $attributes = [$strLis];
        return $this->getRender($urlTemplate, $attributes);

        /*
        // On détermine le nombre de messages non lus dans la boite de réception
        $nbMailsNonLus = 0;
//        $nbMailsNonLus = $this->CopsMailServices->getNombreMailsNonLus();

        $attributes = [
            // Nom Prénom de la personne logguée
            '',
            //$this->CopsPlayer->getFullName(),
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
        ];

      <li class="nav-item d-none d-sm-inline-block"%5$s>
        <a class="nav-link" href="/admin?onglet=inbox"><i class="fa-solid fa-envelope"></i>%4$s</a>
      </li>
      <!-- /.nav-item -->
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item"%5$s>
        <a class="nav-link" data-toggle="dropdown" href="#"><i class="fa-solid fa-bell"></i>%2$s</a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          %3$s
          <div class="dropdown-divider"></div>
          <a href="/admin?onglet=inbox&subOnglet=alert" class="dropdown-item dropdown-footer">Toutes les Notifications</a>
        </div>
      </li>
      <!-- /.nav-item -->
      <li class="nav-item d-none d-sm-inline-block"%5$s>
        <a class="nav-link" href="/admin?onglet=settings"><i class="fa-solid fa-gear"></i></a>
      </li>
      <!-- /.nav-item -->
        */
    }


















    /**
     * @return string
     * @since 1.22.10.18
     * @version v1.23.08.05
     */
    public function getContentPage(): string
    {
        if (!static::isCopsLogged()) {
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
            $attributes = [($strNotification=='' ? 'd-none' : ''), $strNotification];
            return $this->getRender($urlTemplate, $attributes);
        }

        $strOnglet = SessionUtils::fromGet(self::CST_ONGLET);
        $objBean = match ($strOnglet) {
            self::ONGLET_PROFILE => WpPageAdminProfileBean::getStaticWpPageBean($this->slugSubOnglet),
            self::ONGLET_TCHAT => new WpPageAdminTchatBean(),


            self::ONGLET_CALENDAR => WpPageAdminCalendarBean::getStaticWpPageBean($this->slugSubOnglet),
            self::ONGLET_INBOX => WpPageAdminMailBean::getStaticWpPageBean($this->slugSubOnglet),
            self::ONGLET_LIBRARY => WpPageAdminLibraryBean::getStaticWpPageBean($this->slugSubOnglet),
            self::ONGLET_ENQUETE => new WpPageAdminEnqueteBean(),
            self::ONGLET_AUTOPSIE => new WpPageAdminAutopsieBean(),
            default => $this,
        };
        return $objBean->getBoard();
    }

    /**
     * Retourne le contenu de l'interface
     * @return string
     * @since 1.22.10.18
     * @version v1.23.07.02
     */
    public function getBoard(): string
    {
        // Soit on est loggué et on affiche le contenu du bureau du cops
        $urlTemplate = self::WEB_PP_BOARD;
        $attributes = [
            // La sidebar
            $this->getSideBar(),
            // Le contenu de la page
            $this->getOngletContent(),
            // L'id
            $this->objCopsPlayer->getMaskMatricule(),
            // Le nom
            $this->objCopsPlayer->getFullName(),
            // La barre de navigation
            $this->getNavigationBar(),
            // Header
            $this->getContentHeader(),
            // Version
            self::VERSION,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    

     /**
      * @since v1.23.06.05
      * @version v1.23.06.11
      */
     public function getSideBarChildren(array $arrOnglet, string $strOnglet): string
     {
        $ulContent = '';
        foreach ($arrOnglet[self::CST_CHILDREN] as $strSubOnglet => $label) {
            if ($strSubOnglet==$this->urlParams[self::CST_SUBONGLET] ||
                $this->urlParams[self::CST_SUBONGLET]=='date' && $strSubOnglet=='allDates') {
                $extraClass = ' '.self::CST_ACTIVE;
            } else {
                $extraClass = '';
            }
            $aContent  = HtmlUtils::getIcon(self::I_CIRCLE, 'nav-icon').$this->getBalise(self::TAG_P, $label);
            $url = $this->urlOnglet.$strOnglet.'&amp;subOnglet='.$strSubOnglet;
            $liContent = HtmlUtils::getLink($aContent, $url, 'nav-link'.$extraClass);
            $ulContent .= $this->getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>'nav-item']);
        }
        return $ulContent;
     }

    /**
     * @since v1.22.11.11
     * @version v1.23.08.05
     */
    public function getOngletContent(): string
    {
        /*
        $nbDes = SessionUtils::fromGet('nbDes');
        $seuil = SessionUtils::fromGet('seuil');
        $nbBlueDice = SessionUtils::fromGet('nbBlue');
        if ($nbBlueDice=='') {
            $nbBlueDice = 0;
        }
        $nbBlackDice = SessionUtils::fromGet('nbBlack');
        if ($nbBlackDice=='') {
            $nbBlackDice = 0;
        }
        $diceType = SessionUtils::fromGet('diceType', '');
        $blnExplosion = $seuil!=10;
        $nbSucces = 0;
        $nbCritics = 0;

        $strResultat = '';
        for ($i=1; $i<=$nbDes; $i++) {
            if ($i<=$nbBlueDice) {
                $color = 'b';
            } elseif ($i>$nbDes-$nbBlackDice) {
                $color = 'n';
            } else {
                $color = '';
            }
            $strResultat .= DiceUtils::rollSkill($seuil, $nbSucces, $nbCritics, $seuil!=10, $color);
            if ($i<$nbDes) {
                $strResultat .= ', ';
            }
        }

        $strNbSucces = '<span class="'.($nbSucces>0 ? 'deRed' : '').'">'.$nbSucces.'</span> succès';
        $strNbEchecs = '<span class="'.($nbCritics>0 ? '' : '').'">'.$nbCritics.'</span> échecs critiques';
        $attributes = [
            '<span class="jetDeDes">Guillermo a lancé ['.$nbDes.'D'.$seuil.'+] et a obtenu '.$strNbSucces.' et '.$strNbEchecs.' ('.$strResultat.')</span>',
        ];
        */
        return 'WIP';
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
    public function getOngletUrl($urlElements=[])
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
    public function getUrl($urlElements=[])
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
        $arrUrl = [];
        if ($this->catSlug!='') {
            $arrUrl[self::CST_CAT_SLUG] = $this->catSlug;
        }

        if ($nbPages>1) {
            $this->blnHasPagination = true;
            // Le bouton page précédente
            $label = HtmlUtils::getIcon(self::I_CARET_LEFT);
            if ($this->curPage!=1) {
                $btnClass = '';
                $arrUrl[self::CST_CURPAGE] = $this->curPage-1;
                $href = $this->getUrl($arrUrl);
                $btnContent = HtmlUtils::getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = [self::ATTR_CLASS=>$btnClass];
            $strPagination .= HtmlUtils::getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, [$firstItem+1, $lastItem, $nbItems]);
            
            // Le bouton page suivante
            $label = HtmlUtils::getIcon(self::I_CARET_RIGHT);
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $arrUrl[self::CST_CURPAGE] = $this->curPage+1;
                $href = $this->getUrl($arrUrl);
                $btnContent = HtmlUtils::getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = [self::ATTR_CLASS=>$btnClass];
            $strPagination .= self::CST_NBSP.HtmlUtils::getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }
    
}
