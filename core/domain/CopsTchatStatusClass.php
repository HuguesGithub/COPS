<?php
namespace core\domain;

/**
 * Classe CopsTchatStatusClass
 * @author Hugues
 * @since v1.23.08.05
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

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
