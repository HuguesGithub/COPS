<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminLibraryCourseBean
 * @author Hugues
 * @since 1.22.11.05
 * @version v1.23.05.28
 */
class WpPageAdminLibraryCourseBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        
        $urlElements = [self::CST_SUBONGLET => self::CST_LIB_STAGE];
        
        $buttonContent = HtmlUtils::getLink(self::LABEL_COURSES, $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getOngletContent()
    {
        $urlTemplate = self::WEB_PPFS_LIB_COURSES;
        $strContent = '';
        // On doit récupérer l'ensemble des stages et les afficher.
        $objStages = $this->objCopsStageServices->getCopsStageCategories();
        foreach ($objStages as $objStage) {
            $strContent .= $objStage->getBean()->getStageCategoryDisplay();
        }
        
        $attributes = [
            // La liste des stages
            $strContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
