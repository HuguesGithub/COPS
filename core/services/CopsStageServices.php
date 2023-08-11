<?php
namespace core\services;

use core\daoimpl\CopsStageDaoImpl;
use core\domain\CopsStageClass;
use core\domain\CopsStageCategorieClass;

/**
 * Classe CopsStageServices
 * @author Hugues
 * @since 1.22.06.02
 * @version v1.23.08.12
 */
class CopsStageServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.06.02
     * @version v1.23.08.05
     */
    public function __construct()
    {
        $this->initDao();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    private function initDao(): void
    {
        if ($this->objDao==null) {
            $this->objDao = new CopsStageDaoImpl();
        }
    }

    /**
     * @since v1.23.08.12
     */
    public function getStage(int $id): CopsStageClass
    {
        $objs = $this->getStages([self::FIELD_ID=>$id]);
        return empty($objs) ? new CopsStageClass() : array_shift($objs);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.12
     */
    public function getStages(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_STAGE_CAT_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_STAGE_LEVEL] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_STAGE_LIBELLE,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStages($prepAttributes);
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getStageCategorie(int $id): CopsStageCategorieClass
    {
        $attributes = [self::FIELD_ID => $id];
        $objs = $this->getStageCategories($attributes);
        return !empty($objs) ? array_shift($objs) : new CopsStageCategorieClass();
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.12
     */
    public function getStageCategories(array $attributes): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_STAGE_CAT_NAME,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStageCategories($prepAttributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.12
     */
    public function getStageSpecialites(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_STAGE_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStageSpecialites($prepAttributes);
    }

}
