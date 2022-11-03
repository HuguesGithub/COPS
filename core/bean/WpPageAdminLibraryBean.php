<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryBean
 * @author Hugues
 * @since 1.22.05.30
 * @version 1.22.10.20
 */
class WpPageAdminLibraryBean extends WpPageAdminBean
{
    protected $catSlug;
    
    public function __construct()
    {
        parent::__construct();
        /////////////////////////////////////////
        // Définition des services
        $this->copsIndexServices  = new CopsIndexServices();
        $this->wpCategoryServices = new WpCategoryServices();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_LIBRARY;
        $this->titreOnglet = 'Bibliothèque';
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->catSlug = $this->initVar(self::CST_CAT_SLUG);
        // Si catSlug est défini, on récupère la WpCategory associée.
        if ($this->catSlug=='') {
            $this->objWpCategory = new WpCategory();
            $this->objCopsIndexNature = new CopsIndexNature();
        } else {
            // On peut-être dans le cas d'un objet WpCategory
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField('slug', $this->catSlug);
            $name = $this->objWpCategory->getField('name');
            $this->objCopsIndexNature = $this->copsIndexServices->getCopsIndexNatureByName($name);
            // Mais on peut aussi être dans le cas d'un objet WpPost
            // Il faudrait trouver un moyen d'unifier les deux pour que l'accès aux données soit cohérent
            // En effet, je vais avoir besoin de getField('post_name') pour le cas du WpPost.
        }
        
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = array(
            self::CST_LIB_INDEX => array(self::FIELD_ICON => 'book', self::FIELD_LABEL => self::LABEL_INDEX),
            self::CST_LIB_BDD => array(self::FIELD_ICON=>self::I_DATABASE, self::FIELD_LABEL=>self::LABEL_DATABASES),
            //self::CST_LIB_COPS  => array(self::FIELD_ICON => 'users',           self::FIELD_LABEL => 'COPS'),
            //self::CST_LIB_SKILL => array(self::FIELD_ICON => 'toolbox',         self::FIELD_LABEL => 'Compétences'),
            //self::CST_LIB_LAPD  => array(self::FIELD_ICON => 'building-shield', self::FIELD_LABEL => 'LAPD'),
            //self::CST_LIB_STAGE => array(self::FIELD_ICON => 'file-lines',      self::FIELD_LABEL => 'Stages'),
        );
        /////////////////////////////////////////

    }

    /**
     * @return string
     * @since 1.22.10.20
     * @version 1.22.10.20
     */
    public function initBoard()
    {
        $this->buildBreadCrumbs($this->titreOnglet);
        $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
                
        /////////////////////////////////////////
        // Création du Breadcrumbs
        $btnDark = 'btn-dark';
        $btnDarkDisabled = $btnDark.' disabled';
        
        // Le lien vers la Home
        $aContent = $this->getIcon('desktop');
        $buttonContent = $this->getLink($aContent, parent::getPageUrl(), self::CST_TEXT_WHITE);
        $breadCrumbsContent = $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));
        
        // Le lien (ou pas) vers la page principale
        if ($this->slugSubOnglet=='') {
            $breadCrumbsContent .= $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDarkDisabled));
        } else {
            $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
            $breadCrumbsContent .= $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));

            // Le lien (ou pas) vers la catégorie
            if ($this->catSlug=='') {
                $label = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
                $breadCrumbsContent .= $this->getButton($label, array(self::ATTR_CLASS=>$btnDarkDisabled));
            } else {
                $label = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
                $buttonContent = $this->getLink($label, parent::getSubOngletUrl(), self::CST_TEXT_WHITE);
                $breadCrumbsContent .= $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));
                
                $name = $this->objWpCategory->getField('name');
                $breadCrumbsContent .= $this->getButton($name, array(self::ATTR_CLASS=>$btnDarkDisabled));
            }
        }
        
        $this->breadCrumbs = $this->getDiv($breadCrumbsContent, array(self::ATTR_CLASS=>'btn-group float-sm-right'));
        /////////////////////////////////////////
    }
    
    /**
     * @since 1.22.05.30
     * @version 1.22.10.20
     */
    public function getOngletContent()
    {
        switch ($this->slugSubOnglet) {
            case self::CST_LIB_SKILL :
                $strContent = $this->getSubongletSkills();
                break;
            case self::CST_LIB_STAGE :
                $strContent = $this->getSubongletStages();
                break;
            case self::CST_LIB_COPS :
                $strContent = $this->getSubongletCops();
                break;
            case self::CST_LIB_LAPD :
                $strContent = $this->getSubongletLapd();
                break;
            case self::CST_LIB_BDD :
                $objBean = new WpPageAdminLibraryBddBean();
                $strContent = $objBean->getSubongletContent();
                break;
            case self::CST_LIB_INDEX :
                $objBean = new WpPageAdminLibraryIndexBean();
                $strContent = $objBean->getSubongletContent();
                break;
            default :
                $urlTemplate = 'web/pages/public/fragments/public-fragments-article-onglet-menu-panel.php';
                $strContent = '';
                foreach ($this->arrSubOnglets as $subOnglet => $arrSubOnglet) {
                    $attributes = array(
                        self::ONGLET_LIBRARY,
                        $subOnglet,
                        $arrSubOnglet[self::FIELD_LABEL],
                        $arrSubOnglet[self::FIELD_ICON]
                    );
                    $strContent .= $this->getRender($urlTemplate, $attributes);
                }
                break;
        }
      
        return $strContent;
    }

    /**
     * @since 1.22.05.30
     * @version 1.22.05.30
     */
    public function getSubongletSkills()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-skills.php';
        // Récupération des articles Wordpress liés à la catégorie "Compétence"
        $attributes = array(
            // Catégorie "Compétence".
            self::WP_CAT       => self::WP_CAT_ID_SKILL,
            self::SQL_ORDER_BY => self::WP_POSTTITLE,
            self::SQL_ORDER    => self::SQL_ORDER_ASC,
        );
        $WpPosts = $this->WpPostServices->getPosts($attributes);
        // Construction des données du template
        $strContent = '';
        $strAncres  = '';
        $prevAncre  = '';
        $arr_SkillArticles = array();
        //    $strRows    = '';
        while (!empty($WpPosts)) {
            $WpPost = array_shift($WpPosts);
            $arr_SkillArticles[] = $WpPost->getField(self::WP_POSTTITLE);
            $postTitle = str_replace('É', 'E', $WpPost->getField(self::WP_POSTTITLE));
            $postAnchor = substr($postTitle, 0, 1);
            
            if ($prevAncre!=$postAnchor) {
                $prevAncre = $postAnchor;
                $strAncres .= '<li class="nav-item"><a class="nav-link text-white" href="#anchor-'.$postAnchor.'">'.$postAnchor.'</a></li>';
                $strContent .= '<a id="anchor-'.$postAnchor.'"></a>';
            }
            
            $strContent .= WpPost::getBean($WpPost, self::WP_CAT_ID_SKILL)->getContentDisplay();
        }
        
        // Pour le moment, on a un système hybride entre des données en base et des articles Wordpress.
        // A terme, seuls les articles seront utilisés pour l'affichage de l'interface.
        // Les données de la table compétence serviront autrement (notamment ingame)
        // On doit récupérer l'ensemble des compétences et les afficher.
        $Skills = $this->CopsSkillServices->getCopsSkills();
        foreach ($Skills as $Skill) {
            $skillName = $Skill->getField(self::FIELD_SKILL_NAME);
            if (in_array($skillName, $arr_SkillArticles)) {
                continue;
            }
            $strContent .= $Skill->getBean()->getLibraryDisplay();
        }
        
        $attributes = array(
            // La liste des compétences
            $strContent,
            // La liste alphabétique des ancres du haut de page
            $strAncres,
            // Normalement, plus rien après
            '', '', '', '', '', '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }

  /**
   * @since 1.22.06.02
   * @version 1.22.06.02
   */
  public function getSubongletStages()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-stages.php';
    $strContent = '';
    // On doit récupérer l'ensemble des compétences et les afficher.
    $Stages = $this->CopsStageServices->getCopsStageCategories();
    foreach ($Stages as $Stage) {
      $strContent .= $Stage->getBean()->getStageCategoryDisplay();
    }

    $attributes = array(
      // La liste des stages
      $strContent,
      // Normalement, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.26
   * @version 1.22.06.26
   */
  public function getSubongletCops()
  {
    //////////////////////////////////////////////////////////
    // Gestion du Capitaine (potentiellement des Capitaines)
    $attributes[self::SQL_WHERE_FILTERS] = array(
      self::FIELD_ID=>self::SQL_JOKER_SEARCH,
      self::FIELD_MATRICULE=>self::SQL_JOKER_SEARCH,
      self::FIELD_PASSWORD=>self::SQL_JOKER_SEARCH,
      self::FIELD_GRADE=>'Capitaine'
    );
    $CopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
    $strContentCaptains = '';
    while (!empty($CopsPlayers)) {
      $CopsPlayer = array_shift($CopsPlayers);
      $strContentCaptains .= $CopsPlayer->getBean()->getLibraryCard();
    }
    //////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////
    // Gestion des Lieutenants
    $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
    $CopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
    $strContentLieutenants = '';
    $cpt = 0;
    while (!empty($CopsPlayers)) {
      $CopsPlayer = array_shift($CopsPlayers);
      $section = $CopsPlayer->getField(self::FIELD_SECTION);
      if (in_array($section, array('A-Alpha', 'B-Epsilon'))) {
        $strContentLieutenants .= '<div class="col-12 col-md-'.($cpt<3 || $cpt>6 ? 4 : 3).'">'.$CopsPlayer->getBean()->getLibraryCard().'</div>';
      }
      //$cpt++;
    }
    //////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////
    // Gestion des Détectives
    $str_copsDate = get_option(self::CST_CAL_COPSDATE);
    $td = substr($str_copsDate, 9, 2);
    $tm = substr($str_copsDate, 12, 2);
    $tY = substr($str_copsDate, 15, 4);
    $tsToday   = mktime(1, 0, 0, $tm, $td, $tY);

    $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Détective';
    $attributes[self::SQL_ORDER_BY] = self::FIELD_NOM;
    $CopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
    $strContentDetectives = '';
    while (!empty($CopsPlayers)) {
      $CopsPlayer = array_shift($CopsPlayers);
      if ($CopsPlayer->getField(self::FIELD_INTEGRATION_DATE) <= $tY.'-'.$tm.'-'.$td) {
        $strContentDetectives .= '<div class="col-12 col-md-4">'.$CopsPlayer->getBean()->getLibraryCard().'</div>';
      }
    }
    //////////////////////////////////////////////////////////

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-cops.php';
    $attributes = array(
      // Les capitaines
      $strContentCaptains,
      // Les lieutenants
      $strContentLieutenants,
      // Les détectives
      $strContentDetectives,
      // Normalement, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.27
   * @version 1.22.06.27
   */
  public function getSubongletLapd()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-lapd.php';
    $attributes = array(
      // Normalement, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }
  
}
