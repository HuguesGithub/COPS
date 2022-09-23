<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.05.30
 */
class AdminCopsPageBean extends UtilitiesBean
{
  /**
   * Class Constructor
   * @since 1.22.04.27
   * @version 1.22.04.29
   */
  public function __construct()
  {
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
      $CopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
      if (!empty($CopsPlayers)) {
        $this->CopsPlayer = array_shift($CopsPlayers);
        $_SESSION[self::FIELD_MATRICULE] = $_POST[self::FIELD_MATRICULE];
      } else {
        /*
        $CopsPlayer = new CopsPlayer($attributes[self::SQL_WHERE_FILTERS]);
        $this->CopsPlayerServices->insert($CopsPlayer);
        $this->CopsPlayer = $CopsPlayer;
        */
        $_SESSION[self::FIELD_MATRICULE] = 'err_login';
      }
    } elseif (isset($_GET['logout'])) {
      // On cherche a priori à se déconnecter
      unset($_SESSION[self::FIELD_MATRICULE]);
    } elseif (isset($_SESSION[self::FIELD_MATRICULE])) {
      $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
    }

  }

  /**
   * @return string
   * @version 1.22.04.27
   * @since 1.22.04.27
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
   * @since 1.22.04.27
   * @version 1.22.05.30
   */
  public function getContentPage()
  {
    if (!self::isCopsLogged()) {
      // Soit on n'est pas loggué et on affiche la mire d'identification.
      // Celle-ci est invisible et passe visible en cas de souris qui bouge ou touche cliquée.
      $urlTemplate = 'web/pages/public/fragments/public-fragments-section-connexion-panel.php';
      if (isset($_SESSION[self::FIELD_MATRICULE])) {
        switch ($_SESSION[self::FIELD_MATRICULE]) {
          case 'err_login' :
            $strNotification  = "Une erreur est survenue lors de la saisie de votre identifiant et de votre mot de passe.<br>";
            $strNotification .= "L'un des champs était vide, ou les deux ne correspondaient pas à une valeur attendue.<br>";
            $strNotification .= "Veuillez réessayer ou contacter un administrateur.<br><br>";
            unset($_SESSION[self::FIELD_MATRICULE]);
          break;
          default :
            $strNotification = '';
          break;
        }
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
          $Bean = AdminCopsCalendarPageBean::getCalendarBean($this->urlParams[self::CST_SUBONGLET]);
          $returned = $Bean->getBoard();
        break;
        case self::ONGLET_INBOX :
          $Bean = new AdminCopsInboxPageBean();
          $returned = $Bean->getBoard();
        break;
        case self::ONGLET_LIBRARY :
          $Bean = new AdminCopsLibraryPageBean();
          $returned = $Bean->getBoard();
        break;
        case 'player' :
          $Bean = new AdminCopsPlayerPageBean();
          $returned = $Bean->getBoard();
        break;
        case self::ONGLET_PROFILE :
          $Bean = new AdminCopsProfilePageBean();
          $returned = $Bean->getBoard();
        break;
        case self::ONGLET_ENQUETE :
          $Bean = new AdminCopsEnquetePageBean();
          $returned = $Bean->getBoard();
        break;
        case self::ONGLET_DESK   :
        default       :
          $returned = $this->getBoard();
        break;
      }
    } catch (\Exception $Exception) {
      $returned = 'Error';
    }
    return $returned;
  }

  /**
   * Retourne le contenu de l'interface
   * @return string
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function getBoard()
  {
    // Soit on est loggué et on affiche le contenu du bureau du cops
    $urlTemplate = 'web/pages/public/public-board.php';
    $attributes = array(
      // La sidebar
      $this->getSideBar(),
      // Le contenu de la page
      '',
      // L'id
      $this->CopsPlayer->getMaskMatricule(),
      // Le nom
      $this->CopsPlayer->getFullName(),
      // La barre de navigation
      $this->getNavigationBar(),

      '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /*
   * @since 1.22.06.09
   * @version 1.22.06.21
   */
  public function buildBreadCrumbs($label, $slug=null, $hasDropdown=false)
  {
    $this->breadCrumbs  = '<div class="btn-group float-sm-right">';
    // Le lien vers la Home
    $this->breadCrumbs .= '<button type="button" class="btn btn-sm btn-dark"><a class="text-white" href="/admin/"><i class="fa-solid fa-desktop"></i></a></button>';
    // Le lien intermédiaire ou final si slug vaut null
    if ($slug==null) {
      $this->breadCrumbs .= '<button type="button" class="btn btn-sm btn-dark disabled">'.$label.'</button>';
    } else {
      $this->breadCrumbs .= '<button type="button" class="btn btn-sm btn-dark"><a class="text-white" href="/admin?onglet='.$slug.'">'.$label.'</a></button>';
      // Le lien ou le dropdown selon le type de mixed
      if ($hasDropdown===true) {
        $this->breadCrumbs .= '<div class="btn-group">';
        $this->breadCrumbs .= '<button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-toggle="dropdown">'.$this->arrSubOnglets[$this->subOnglet]['label'].'</button>';
        $this->breadCrumbs .= '<div class="dropdown-menu">';
        foreach ($this->arrSubOnglets as $subOnglet => $arrData) {
          if ($arrData['label']=='' || !isset($arrData[self::FIELD_ICON])) {
            continue;
          }
          $url = '/admin?onglet='.$slug.'&subOnglet='.$subOnglet.(isset($arrData['url']) ? '&'.$arrData['url'] : '');
          $this->breadCrumbs .= '<a class="dropdown-item btn-sm" href="'.$url.'">'.$arrData['label'].'</a>';
        }
        $this->breadCrumbs .= '</div>';
        $this->breadCrumbs .= '</div>';
      } elseif ($hasDropdown!==false) {
        $this->breadCrumbs .= '<button type="button" class="btn btn-sm btn-dark disabled">'.$hasDropdown.'</button>';
      }
    }
    $this->breadCrumbs .= '</div>';
  }

  /*
   * @since 1.22.04.27
   * @version 1.22.05.30
   */
  protected function getSideBar()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-sidebar.php';
    if ($_SESSION[self::FIELD_MATRICULE]=='Guest') {
      $arrSidebarContent = array(
        'COPS' => array(
          self::ONGLET_DESK => array(
            self::FIELD_ICON  => 'fa-solid fa-desktop',
            self::FIELD_LABEL => 'Bureau',
          ),
          self::ONGLET_LIBRARY => array(
            self::FIELD_ICON  => 'fa-solid fa-book',
            self::FIELD_LABEL => 'Bibliothèque',
          ),
        ),
      );
    } else {
      $arrSidebarContent = array(
        'COPS' => array(
          self::ONGLET_DESK => array(
            self::FIELD_ICON  => 'fa-solid fa-desktop',
            self::FIELD_LABEL => 'Bureau',
          ),
          self::ONGLET_INBOX => array(
            self::FIELD_ICON  => 'fa-solid fa-envelope',
            self::FIELD_LABEL => 'Messagerie',
          ),
            self::ONGLET_ENQUETE => array(
            self::FIELD_ICON  => 'fa-solid fa-file-lines',
            self::FIELD_LABEL => 'Enquêtes',
          ),
          self::ONGLET_CALENDAR => array(
            self::FIELD_ICON  => 'fa-solid fa-calendar-days',
            self::FIELD_LABEL => 'Calendrier',
            'children'        => array(
              self::CST_CAL_MONTH  => 'Calendrier',
              self::CST_CAL_EVENT  => 'Événements',
              self::CST_CAL_PARAM  => 'Paramètres',
            ),
          ),
          self::ONGLET_ARCHIVE => array(
            self::FIELD_ICON  => 'fa-solid fa-box-archive',
            self::FIELD_LABEL => 'Archives',
          ),
          self::ONGLET_LIBRARY => array(
            self::FIELD_ICON  => 'fa-solid fa-book',
            self::FIELD_LABEL => 'Bibliothèque',
          ),
        ),
      );
    }
    /*
        'player' => array(
          'icon' => 'fa-solid fa-user',
          'label' => 'Personnage',
          'children' => array(
            'player-carac' => 'Caractéristiques',
            'player-comps' => 'Compétences',
            'player-story' => 'Background',
          ),
        ),
    */

    $sidebarContent = '';
    foreach ($arrSidebarContent as $strHeader => $arrItems) {
      //$sidebarContent .= '<li class="nav-header">'.$strHeader.'</li>';
      foreach ($arrItems as $strOnglet => $arrOnglet) {
        $sidebarContent .= '<li class="nav-item'.(($strOnglet==$this->urlParams[self::CST_ONGLET])?' menu-open' : '').'"><a href="/admin?onglet='.$strOnglet.'" class="nav-link';
        $sidebarContent .= (($strOnglet==$this->urlParams[self::CST_ONGLET])?' active' : '').'"><i class="nav-icon '.$arrOnglet['icon'];
        $sidebarContent .= '"></i><p>'.$arrOnglet['label'];
        if (isset($arrOnglet['children'])) {
          $sidebarContent .= '<i class="fas fa-angle-left right"></i>';
        }
        $sidebarContent .= '</p></a>';
        if (isset($arrOnglet['children'])) {
          $sidebarContent .= '<ul class="nav nav-treeview">';
          foreach ($arrOnglet['children'] as $strSubOnglet => $label) {
            $extraClass = (($strSubOnglet==$this->urlParams['subOnglet'])?' active' : '');
            // Cas spéciaux :
            if ($this->urlParams['subOnglet']=='date' && $strSubOnglet=='allDates') {
              $extraClass = ' active';
            }
            $sidebarContent .= '<li class="nav-item"><a href="/admin?onglet='.$strOnglet.'&amp;subOnglet='.$strSubOnglet.'" class="nav-link'.$extraClass.'"><i class="far fa-circle nav-icon"></i><p>'.$label.'</p></a></li>';
          }
          $sidebarContent .= '</ul>';
        }
        $sidebarContent .= '</li>';
      }
    }

    $str_copsDate = get_option('cops_date');
    $his = substr($str_copsDate, 0, 8);
    $d = substr($str_copsDate, 9, 2);
    $m = substr($str_copsDate, 12, 2);
    $y = substr($str_copsDate, 15, 4);

    $attributes = array(
      $sidebarContent,
      // La date
      date('D m-d-Y', mktime(1, 0, 0, $m, $d, $y)),
      // L'heure
      $his,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /*
   * @since 1.22.04.27
   * @version 1.22.04.29
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

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
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
   * @since 1.22.05.04
   * @version 1.22.05.04
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




}