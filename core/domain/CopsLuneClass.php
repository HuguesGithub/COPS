<?php
namespace core\domain;

use core\bean\CopsLuneBean;
use core\utils\DateUtils;

/**
 * Classe CopsLuneClass
 * @author Hugues
 * @since 1.23.4.27
 * @version v1.23.04.30
 */
class CopsLuneClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $idLune;
    protected $dateLune;
    protected $heureLune;
    protected $typeLune;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsLuneClass';
    }

    /**
     * @since 1.23.4.27
     * @version v1.23.4.30
     */
    public static function convertElement($row): CopsLuneClass
    {
        return parent::convertRootElement(new CopsLuneClass(), $row);
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getBean(): CopsLuneBean
    { return new CopsLuneBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getMoonStatus(): string
    {
        return match ($this->typeLune) {
            'newmoon' => 'Nouvelle Lune',
            'firstquarter' => 'Premier Quartier',
            'fullmoon' => 'Pleine Lune',
            'thirdquarter' => 'Dernier Quartier',
            default => 'Phase de lune incorrecte',
        };
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getDateHeure(): string
    {
        return DateUtils::getStrDate('d M y H:i:s', $this->dateLune.' '.$this->heureLune);
    }

}
