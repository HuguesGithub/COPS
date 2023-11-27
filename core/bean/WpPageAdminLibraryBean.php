<?php
namespace core\bean;

use core\domain\CopsIndexNatureClass;
use core\domain\WpCategoryClass;
use core\services\CopsIndexServices;
use core\services\CopsStageServices;
use core\services\WpCategoryServices;
use core\services\WpPostServices;
use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminLibraryBean
 * @author Hugues
 * @since 1.22.05.30
 * @version v1.23.12.02
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
        $this->objCopsStageServices  = new CopsStageServices();
        $this->wpCategoryServices = new WpCategoryServices();
        $this->objWpPostServices = new WpPostServices();
        
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
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField(self::FIELD_SLUG, $this->catSlug);
            $name = $this->objWpCategory->getField(self::FIELD_NAME);
            $this->objCopsIndexNature = $this->objCopsIndexServices->getCopsIndexNatureByName($name);
        }
        
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_LIB_INDEX => [self::FIELD_ICON => self::I_BOOK, self::FIELD_LABEL => self::LABEL_INDEX],
            self::CST_LIB_BDD   => [self::FIELD_ICON => self::I_DATABASE, self::FIELD_LABEL => self::LABEL_DATABASES],
            self::CST_LIB_SKILL => [self::FIELD_ICON => self::I_TOOLBOX, self::FIELD_LABEL => self::LABEL_SKILLS],
            self::CST_LIB_STAGE => [self::FIELD_ICON => self::I_FILE_LINES, self::FIELD_LABEL => self::LABEL_COURSES],
            self::CST_LIB_COPS  => [self::FIELD_ICON => self::I_USERS, self::FIELD_LABEL => 'COPS']
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction du Breadcrumbs
        if ($this->slugSubOnglet=='') {
            $spanAttributes = [self::ATTR_CLASS => self::CST_TEXT_WHITE];
            $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreOnglet, $spanAttributes);
            $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        } else {
            $buttonContent = HtmlUtils::getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
            $buttonAttributes = [self::ATTR_CLASS=>($this->btnDark)];
        }
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
    }

    /**
     * @since 1.22.05.30
     * @version v1.23.05.28
     */
    public function getOngletContent(): string
    {
        $urlTemplate = self::WEB_PPFS_ONGLET_MENU_PANEL;
        $strContent = '';
        foreach ($this->arrSubOnglets as $subOnglet => $arrSubOnglet) {
            $attributes = [
                self::ONGLET_LIBRARY,
                $subOnglet,
                $arrSubOnglet[self::FIELD_LABEL],
                $arrSubOnglet[self::FIELD_ICON]
            ];
            $strContent .= $this->getRender($urlTemplate, $attributes);
        }
      
        return HtmlUtils::getDiv($strContent, [self::ATTR_CLASS=>'row']);
    }
    
   /**
    * @since v1.22.11.11
    * @version v1.22.11.11
    */
    public static function getStaticWpPageBean($slugSubContent)
    {
        $objBean = null;
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
