<?php
namespace core\domain;

/**
 * Classe CopsSkillSpecClass
 * @since v1.23.06.25
 * @version v1.23.06.25
 */
class CopsSkillSpecClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $specName;
    protected $skillId;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsSkillSpecClass';
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsSkillSpecClass(), $row); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
