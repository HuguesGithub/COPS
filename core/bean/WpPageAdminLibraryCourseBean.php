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
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getSubongletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-stages.php';
        $strContent = '';
        // On doit récupérer l'ensemble des stages et les afficher.
        $Stages = $this->objCopsStageServices->getCopsStageCategories();
        foreach ($Stages as $Stage) {
            $strContent .= $Stage->getBean()->getStageCategoryDisplay();
        }
        
        $attributes = array(
            // La liste des stages
            $strContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
