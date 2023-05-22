<?php
namespace core\domain;

use core\bean\CopsEventDateAlldayBean;
use core\bean\CopsEventDateLongBean;
use core\bean\CopsEventDateDotBean;
use core\services\CopsEventServices;

/**
 * Classe CopsEventDate
 * @author Hugues
 * @since 1.22.11.25
 * @version v1.23.05.21
 */
class CopsEventDateClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $eventId;
    protected $dStart;
    protected $dEnd;
    protected $tStart;
    protected $tEnd;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since 1.22.06.13
     * @version v1.23.05.14
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsEventDateClass';
        // On initialise l'event source
        $this->objCopsEvent = $this->getCopsEvent();
    }
    /**
     * @version 1.22.06.13
     * @since 1.22.06.13
     */
    public static function convertElement($row): CopsEventDateClass
    { return parent::convertRootElement(new CopsEventDateClass(), $row); }
    
    /**
     * @version 1.22.06.13
     * @since v1.23.05.21
     */
    public function getBean()
    {
        $objCopsEvent = $this->getCopsEvent();
        if ($objCopsEvent->isAllDayEvent()) {
            $objBean = new CopsEventDateAlldayBean($this);
        } else {
            $objBean = new CopsEventDateDotBean($this);
        }
        return $objBean;
    }

    /**
     * @since 1.22.11.25
     * @version v1.23.05.14
     */
    public function getCopsEvent(): CopsEventClass
    {
        $this->objCopsEventServices = new CopsEventServices();
        return $this->objCopsEventServices->getCopsEvent($this->eventId);
    }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function getAmPmTime(string $field, bool $blnAmPm=true): string
    {
        $intTime = $field==self::FIELD_TSTART ? $this->tStart : $this->tEnd;
        $m = $intTime%60;
        $h = floor($intTime/60);

        if (!$blnAmPm) {
            $objCopsEvent = $this->getCopsEvent();
            $strTime  = $objCopsEvent->getField(self::FIELD_HEURE_DEBUT).' - ';
            $strTime .= $objCopsEvent->getField(self::FIELD_HEURE_FIN);
            return $strTime;
        }

        if ($h>12) {
            $hmod = $h-12;
            $ampm = 'p';
        } else {
            $hmod = $h;
            $ampm = 'a';
        }
        return $hmod.($m!=0 ? ':'.str_pad($m, 2, '0', STR_PAD_LEFT) : '').$ampm;
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function isFirstDay(string $curDate): bool
    {
        return $curDate==$this->dStart;
    }
}
