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
            $this->objCopsIndexNature = $this->copsIndexServices->getCopsIndexNatureByName($name);
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
            $breadCrumbsContent .= $this->getButton($this->titreOnglet, array(self::ATTR_CLASS=>$btnDarkDisabled));
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
     * @version 1.22.11.05
     */
    public function getOngletContent()
    {
        switch ($this->slugSubOnglet) {
            case self::CST_LIB_SKILL :
                $objBean = new WpPageAdminLibrarySkillBean();
                $strContent = $objBean->getSubongletContent();
                break;
            case self::CST_LIB_STAGE :
                $objBean = new WpPageAdminLibraryCourseBean();
                $strContent = $objBean->getSubongletContent();
                break;
            case self::CST_LIB_COPS :
                $objBean = new WpPageAdminLibraryCopsBean();
                $strContent = $objBean->getSubongletContent();
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
