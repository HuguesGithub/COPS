<?php
namespace core\bean;

use core\domain\CopsStageClass;
use core\services\CopsStageServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsStageBean
 * @author Hugues
 * @since 1.22.06.03
 * @version 1.22.06.03
 */
class CopsStageBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objStage = ($obj==null ? new CopsStageClass() : $obj);

        $this->objCopsStageServices = new CopsStageServices();
    }

    /**
     * @since 1.22.06.03
     * @version 1.22.06.03
     */
    public function getStageDisplay()
    {
        $urlTemplate = self::WEB_PPFA_LIB_COURSE;

        $strCapacitesSpeciales = '';
        $objsCapaSpec = $this->objCopsStageServices->getStageSpecs($this->objStage->getField(self::FIELD_ID));
        while (!empty($objsCapaSpec)) {
            $objCapaSpec = array_shift($objsCapaSpec);
            $strCapacitesSpeciales .= '<dt>'.$objCapaSpec->getField(self::FIELD_SPEC_NAME).'</dt>';
            $strCapacitesSpeciales .= '<dd>'.$objCapaSpec->getField(self::FIELD_SPEC_DESC).'</dd>';
        }

        $attributes = [
            // Le nom du stage
            $this->objStage->getField(self::FIELD_STAGE_LIBELLE),
            // Le niveau du stage
            'lvl'.$this->objStage->getField(self::FIELD_STAGE_LEVEL),
            // Les Pré Requis
            $this->objStage->getField(self::FIELD_STAGE_REQUIS),
            // Le Cumul éventuel
            $this->objStage->getField(self::FIELD_STAGE_CUMUL),
            // La Description
            $this->objStage->getField(self::FIELD_STAGE_DESC),
            // Le Bonus éventuel
            $this->objStage->getField(self::FIELD_STAGE_BONUS),
            // La liste des capacités spéciales
            '<dl>'.$strCapacitesSpeciales.'</dl>',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
