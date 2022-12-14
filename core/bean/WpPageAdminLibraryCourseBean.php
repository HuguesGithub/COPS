<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryCourseBean
 * @author Hugues
 * @since 1.22.11.05
 * @version 1.22.11.05
 */
class WpPageAdminLibraryCourseBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objCopsStageServices = new CopsStageServices();
        
        $urlElements = array(
            self::CST_SUBONGLET => self::CST_LIB_STAGE,
        );
        
        $buttonContent = $this->getLink('Stages', $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-stages.php';
        $strContent = '';
        // On doit récupérer l'ensemble des stages et les afficher.
        $objStages = $this->objCopsStageServices->getCopsStageCategories();
        foreach ($objStages as $objStage) {
            $strContent .= $objStage->getBean()->getStageCategoryDisplay();
        }
        
        $attributes = array(
            // La liste des stages
            $strContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
