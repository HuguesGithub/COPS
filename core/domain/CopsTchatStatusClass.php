<?php
namespace core\domain;

use core\bean\CopsTchatStatusBean;
use core\domain\CopsPlayerClass;
use core\services\CopsPlayerServices;

/**
 * Classe CopsTchatStatusClass
 * @author Hugues
 * @since v1.23.08.05
 * @version v1.23.08.12
 */
class CopsTchatStatusClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $salonId;
    protected $playerId;
    protected $lastRefreshed;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.08.05
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsTchatStatusClass';
    }

    /**
     * @since v1.23.08.05
     */
    public static function convertElement($row): CopsTchatStatusClass
    {
        return parent::convertRootElement(new CopsTchatStatusClass(), $row);
    }

    /**
     * @since v1.23.08.12
     */
    public function getBean(): CopsTchatStatusBean
    { return new CopsTchatStatusBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.08.12
     */
    public function getPlayer(): CopsPlayerClass
    {
        $objServices = new CopsPlayerServices();
        return $objServices->getPlayer($this->playerId);
    }

}
