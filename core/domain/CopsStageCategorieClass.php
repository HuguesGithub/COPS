<?php
namespace core\domain;

use core\bean\CopsStageCategorieBean;
use core\services\CopsStageServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsStageCategorieClass
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.03
 */
class CopsStageCategorieClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $stageCategorie;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.06.02
     * @since 1.22.06.02
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsStageCategorieClass';

        $this->objCopsStageServices = new CopsStageServices();
    }
    /**
     * @param array $row
     * @return CopsStageCategorieClass
     * @version 1.22.06.02
     * @since 1.22.06.02
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsStageCategorieClass(), $row); }

    /*
     * @version 1.22.06.02
     * @since 1.22.06.02
     */
    public function getBean()
    { return new CopsStageCategorieBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /*
     * @version 1.22.06.03
     * @since 1.22.06.03
     */
    public function getStages()
    {
        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS] = [self::FIELD_ID           => self::SQL_JOKER_SEARCH, self::FIELD_STAGE_CAT_ID => $this->id, self::FIELD_STAGE_LEVEL  => self::SQL_JOKER_SEARCH];
        return $this->objCopsStageServices->getStages($attributes);
    }
}
