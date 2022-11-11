<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryBean
 * @author Hugues
 * @since 1.22.05.30
 * @version 1.22.11.05
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
        $this->titreOnglet = self::LABEL_LIBRARY;
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->catSlug = $this->initVar(self::CST_CAT_SLUG);
        // Si catSlug est défini, on récupère la WpCategory associée.
        if ($this->catSlug=='') {
            $this->objWpCategory = new WpCategory();
            $this->objCopsIndexNature = new CopsIndexNature();
        } else {
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField('slug', $this->catSlug);
            $name = $this->objWpCategory->getField('name');
            if ($name=='') {
                $this->objCopsIndexNature = $this->copsIndexServices->getCopsIndexNatureByName($name);
            } else {
                $name = 'TODO';
            }
        }
        
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = array(
            self::CST_LIB_INDEX => array(self::FIELD_ICON => 'book', self::FIELD_LABEL => self::LABEL_INDEX),
            self::CST_LIB_BDD   => array(self::FIELD_ICON => 'database', self::FIELD_LABEL => self::LABEL_DATABASES),
            self::CST_LIB_SKILL => array(self::FIELD_ICON => 'toolbox', self::FIELD_LABEL => self::LABEL_SKILLS),
            self::CST_LIB_STAGE => array(self::FIELD_ICON => 'file-lines', self::FIELD_LABEL => self::LABEL_COURSES),
            self::CST_LIB_COPS  => array(self::FIELD_ICON => 'users', self::FIELD_LABEL => 'COPS'),
            //self::CST_LIB_LAPD  => array(self::FIELD_ICON => 'building-shield', self::FIELD_LABEL => 'LAPD'),
        );
        /////////////////////////////////////////

        $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->slugSubOnglet==''?$this->btnDisabled:$this->btnDark));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
    }

    /**
     * @return string
     * @since 1.22.10.20
     * @version 1.22.10.20
     *
    public function initBoard()
    {
        $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
        $this->buildBreadCrumbs();
        
         
        /*
        $this->buildBreadCrumbs($this->titreOnglet);
                
        

        // Le lien (ou pas) vers la page principale
        if ($this->slugSubOnglet=='') {
            $breadCrumbsContent .= $this->getButton($this->titreOnglet, array(self::ATTR_CLASS=>$btnDarkDisabled));
        } else {

            // Le lien (ou pas) vers la catégorie
            if ($this->catSlug=='') {
                $label = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
                $breadCrumbsContent .= $this->getButton($label, array(self::ATTR_CLASS=>$btnDarkDisabled));
            } else {
                
                $name = $this->objWpCategory->getField('name');
                $breadCrumbsContent .= $this->getButton($name, array(self::ATTR_CLASS=>$btnDarkDisabled));
            }
        }
        
         *
    }
    /*
    public function buildBreadCrumbs()
    {
        /////////////////////////////////////////
        // Création du Breadcrumbs
        $btnDark = 'btn-dark';
        $btnDarkDisabled = $btnDark.' disabled';
        
        // Ca ne devrait pas être défini ici, mais plus haut.
        // Le lien vers la Home
        $aContent = $this->getIcon('desktop');
        $buttonContent = $this->getLink($aContent, parent::getPageUrl(), self::CST_TEXT_WHITE);
        $breadCrumbsContent = $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));
        
        // Ca ne devrait pas être défini ici, mais plus haut.
        // Le lien vers Bibliothèque
        $btnDark = 'btn-dark';
        $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
        $breadCrumbsContent .= $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));
        
        if ($this->catSlug=='') {
            $label = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
            $breadCrumbsContent .= $this->getButton($label, array(self::ATTR_CLASS=>$btnDarkDisabled));
        } else {
            $label = $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL];
            $buttonContent = $this->getLink($label, parent::getSubOngletUrl(), self::CST_TEXT_WHITE);
            $breadCrumbsContent .= $this->getButton($buttonContent, array(self::ATTR_CLASS=>$btnDark));
        }
        $this->breadCrumbs = $this->getDiv($breadCrumbsContent, array(self::ATTR_CLASS=>'btn-group float-sm-right'));
        /////////////////////////////////////////
    }
    *
    /**
     * @since 1.22.05.30
     * @version 1.22.11.05
     */
    public function getOngletContent()
    {
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
      
        return $strContent;
    }

  /**
   * @since 1.22.06.27
   * @version 1.22.06.27
   *
  public function getSubongletLapd()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-lapd.php';
    $attributes = array(
      // Normalement, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }
 
  public function buildBreadCrumbs()
  {
      parent::buildBreadCrumbs();
      
      //        $this->breadCrumbs = $this->getDiv($breadCrumbsContent, array(self::ATTR_CLASS=>'btn-group float-sm-right'));
      /////////////////////////////////////////
  }
  */
    
  /**
   * @since v1.22.11.11
   * @version v1.22.11.11
   */
  public static function getStaticWpPageBean($slugSubContent)
  {
      switch ($slugSubContent) {
          case self::CST_LIB_SKILL :
              $objBean = new WpPageAdminLibrarySkillBean();
              break;
          case self::CST_LIB_STAGE :
              $objBean = new WpPageAdminLibraryCourseBean();
              break;
          case self::CST_LIB_COPS :
              $objBean = new WpPageAdminLibraryCopsBean();
              break;
          case self::CST_LIB_LAPD :
//              $strContent = $this->getSubongletLapd();
              break;
          case self::CST_LIB_BDD :
              $objBean = new WpPageAdminLibraryBddBean();
              break;
          case self::CST_LIB_INDEX :
              $objBean = new WpPageAdminLibraryIndexBean();
              break;
          default :
              $objBean = new WpPageAdminLibraryBean();
              break;
      }
      
      return $objBean;
  }
}
