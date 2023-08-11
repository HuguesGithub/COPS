<?php
namespace core\bean;

use core\services\CopsPlayerServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;

/**
 * CopsTchatStatusBean
 * @author Hugues
 * @since v1.23.08.12
 */
class CopsTchatStatusBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.08.12
     */
    public function getContactRow(): string
    {
        $objPlayer = $this->obj->getPlayer();
        $strName = HtmlUtils::getDiv($objPlayer->getFullName(), [self::ATTR_CLASS=>'name']);
        
        $lastRefreshed = $this->obj->getField(self::FIELD_LAST_REFRESHED);
        $pattern = "/[ :-]/";
        [$y, $m, $d, $h, $i, $s] = preg_split($pattern, $lastRefreshed);
        $intDif = time()-mktime($h, $i, $s, $m, $d, $y);
        $lessThan5Minutes = $intDif<=60*5;

        if ($lessThan5Minutes) {
            $strIcon  = HtmlUtils::getIcon(self::I_CIRCLE, 'online');
            $strIcon .= ' online';
        } else {
            $strIcon = HtmlUtils::getIcon(self::I_CIRCLE, 'offline');
            $strIcon .= ' offline depuis ';
            if ($intDif<60*60) {
                $strIcon .= ceil($intDif/60).' minutes';
            } elseif ($intDif<60*60*24) {
                $strIcon .= ceil($intDif/(60*60)).' heures';
            } else {
                $strIcon .= 'le '.$d.' '.DateUtils::$arrShortMonths[(int)$m];
            }
        }
        $strStatut = HtmlUtils::getDiv($strIcon, [self::ATTR_CLASS => 'status']);

        return HtmlUtils::getDiv($strName.$strStatut, [self::ATTR_CLASS=>'about']);
    }

}
