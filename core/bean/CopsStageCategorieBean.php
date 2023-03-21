<?php
namespace core\bean;

use core\domain\CopsStageCategorieClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsStageCategorieBean
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.03
 */
class CopsStageCategorieBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsStageCategorie = ($obj==null ? new CopsStageCategorieClass() : $obj);
    }

    /**
     * @since 1.22.05.30
     * @version 1.22.06.03
     */
    public function getStageCategoryDisplay()
    {
        $urlTemplate = self::WEB_PPFA_LIB_COURSE_CATEG;
        $strContent  = '';

        $objsStage = $this->objCopsStageCategorie->getStages();
        while (!empty($objsStage)) {
            $objStage = array_shift($objsStage);
            $strContent .= $objStage->getBean()->getStageDisplay();
        }

        $attributes = array(
            // Le nom de la compétence
            $this->objCopsStageCategorie->getField(self::FIELD_STAGE_CAT_NAME),
            // Liste des Stages de cette Catégorie
            $strContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
