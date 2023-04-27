<?php
namespace core\domain;

use core\bean\CopsSoleilBean;
use core\utils\DateUtils;

/**
 * Classe CopsSoleilClass
 * @author Hugues
 * @since 1.23.4.20
 * @version v1.23.04.30
 */
class CopsSoleilClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $dateSoleil;
    protected $heureLever;
    protected $heureCoucher;
    protected $heureCulmine;
    protected $dureeJour;
    protected $heureCivilAm;
    protected $heureCivilPm;
    protected $heureNautikAm;
    protected $heureNautikPm;
    protected $heureAstroAm;
    protected $heureAstroPm;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsSoleilClass';
    }

    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public static function convertElement($row): CopsSoleilClass
    {
        return parent::convertRootElement(new CopsSoleilClass(), $row);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getBean(): CopsSoleilBean
    { return new CopsSoleilBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getDureeJournee(): string
    {
        $hs = substr($this->heureLever, 0, 2);
        $is = substr($this->heureLever, -2);
        $he = substr($this->heureCoucher, 0, 2);
        $ie = substr($this->heureCoucher, -2);

        $duree = (60-$is) + $ie + ($he-$hs-1)*60;

        $i = $duree%60;
        $h = floor($duree/60);

        return $h.':'.$i;
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getHeaderDate(): string
    {
        [, $m, $d] = explode('-', $this->dateSoleil);
        return $d.' '.DateUtils::$arrShortMonths[$m*1];
    }

}
