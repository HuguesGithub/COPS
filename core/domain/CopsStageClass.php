<?php
declare(strict_types=1);

namespace core\domain;

use core\bean\CopsStageBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsStageClass
 * @author Hugues
 * @version 1.22.06.03
 * @since 1.22.06.03
 */
class CopsStageClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    protected $stageCategorieId;
    protected $stageLibelle;
    protected $stageNiveau;
    protected $stageReference;
    protected $stagePreRequis;
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
     * @param array $row
     * @version 1.22.06.03
     * @since 1.22.06.03
     */
    public static function convertElement(array $row): CopsStageClass
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

}
