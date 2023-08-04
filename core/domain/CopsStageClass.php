<?php
namespace core\domain;

use core\bean\CopsStageBean;
use core\services\CopsStageServices;

/**
 * Classe CopsStageClass
 * @author Hugues
 * @since 1.22.06.03
 * @version v1.23.08.05
 */
class CopsStageClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $stageCategorieId;
    protected $stageLibelle;
    protected $stageNiveau;
    protected $stageReference;
    protected $stagePreRequis;
    protected $stagePreRequisNew;
    protected $stageCumul;
    protected $stageDescription;
    protected $stageBonus;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.06.03
     * @since 1.22.06.03
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsStageClass';
    }

    /**
     * @since 1.22.06.03
     * @version v1.23.08.05
     */
    public static function convertElement($row): CopsStageClass
    { return parent::convertRootElement(new CopsStageClass(), $row); }

    /**
     * @version 1.22.06.03
     * @since 1.22.06.03
     */
    public function getBean(): CopsStageBean
    { return new CopsStageBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    public function getCategorie(): CopsStageCategorieClass
    {
        $objService = new CopsStageServices();
        return $objService->getStageCategorie($this->stageCategorieId);
    }
}
