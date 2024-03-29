<?php
namespace core\domain;

/**
 * Classe CopsStageCapaciteSpecialeClass
 * @author Hugues
 * @since 1.22.07.06
 * @version v1.23.08.05
 */
class CopsStageCapaciteSpecialeClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $specName;
    protected $specDescription;
    protected $stageId;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.07.06
     * @since 1.22.07.06
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsStageCapaciteSpecialeClass';
    }

    /**
     * @param array $row
     * @return CopsStageCapaciteSpecialeClass
     * @version 1.22.07.06
     * @since 1.22.07.06
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsStageCapaciteSpecialeClass(), $row); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
