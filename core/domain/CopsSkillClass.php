<?php
namespace core\domain;

use core\bean\CopsSkillBean;
use core\services\CopsSkillServices;

/**
 * Classe CopsSkillClass
 * @author Hugues
 * @since 1.22.05.30
 * @version v1.23.08.12
 */
class CopsSkillClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $skillName;
    protected $skillDescription;
    protected $skillUses;
    protected $specLevel;
    protected $padUsable;
    protected $reference;
    protected $defaultAbility;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.05.30
     * @since 1.22.05.30
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsSkillClass';

        $this->objCopsSkillServices = new CopsSkillServices();
    }

    /**
     * @param array $row
     * @return CopsSkill
     * @version 1.22.05.30
     * @since 1.22.05.30
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsSkillClass(), $row); }

    /**
     * @return CopsSkillBean
     * @version 1.22.05.30
     * @since 1.22.05.30
     */
    public function getBean()
    { return new CopsSkillBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since 1.22.05.30
     * @version 1.22.05.30
     */
    public function getSpecialisations()
    { return $this->objCopsSkillServices->getSkillSpecs($this->id); }

}
