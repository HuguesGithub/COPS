<?php
namespace core\domain;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsSoleilClass
 * @author Hugues
 * @since 1.23.4.20
 * @version 1.23.4.20
 */
class CopsSoleilClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $dateSoleil;
    protected $heureLever;
    protected $heureCoucher;

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
    public static function convertElement(array $row): CopsSoleilClass
    {
        return parent::convertRootElement(new CopsSoleilClass(), $row);
    }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

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

}
