<?php
namespace core\bean;

use core\domain\CopsIndexNatureClass;
use core\domain\WpCategoryClass;
use core\services\CopsIndexServices;
use core\services\WpCategoryServices;

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
        $this->objCopsIndexServices  = new CopsIndexServices();
        $this->wpCategoryServices = new WpCategoryServices();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_LIBRARY;
        $this->titreOnglet = self::LABEL_LIBRARY;
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->catSlug = $this->initVar(self::CST_CAT_SLUG);
        // Si catSlug est défini, on récupère la WpCategory associée.
        if ($this->catSlug=='') {
            $this->objWpCategory = new WpCategoryClass();
            $this->objCopsIndexNature = new CopsIndexNatureClass();
        } else {
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField('slug', $this->catSlug);
            $name = $this->objWpCategory->getField('name');
            $this->objCopsIndexNature = $this->objCopsIndexServices->getCopsIndexNatureByName($name);
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

        /////////////////////////////////////////
        // Construction du Breadcrumbs
        if ($this->slugSubOnglet=='') {
            $spanAttributes = array(
                self::ATTR_CLASS => self::CST_TEXT_WHITE,
            );
            $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreOnglet, $spanAttributes);
            $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        } else {
            $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
            $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDark));
        }
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
    }

    /**
     * @since 1.22.05.30
     * @version 1.22.11.05
     */
    public function getOngletContent()
    {
        $urlTemplate = self::WEB_PPFS_ONGLET_MENU_PANEL;
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
      
        return $this->getDiv($strContent, array(self::ATTR_CLASS=>'row'));
    }
    
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
                // TODO
                //$objBean = new WpPageAdminLibraryLapsBean();
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
