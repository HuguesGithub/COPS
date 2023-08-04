<?php
namespace core\services;

use core\daoimpl\CopsStageDaoImpl;
use core\domain\CopsStageCategorieClass;

/**
 * Classe CopsStageServices
 * @author Hugues
 * @since 1.22.06.02
 * @version v1.23.08.05
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
     * @since v1.23.07.19
     * @version v1.23.08.05
     */
    public function getStages(array $attributes=[]): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $stageCategId = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_CAT_ID] ?? self::SQL_JOKER_SEARCH;
        $stageNiveau = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_LEVEL] ?? self::SQL_JOKER_SEARCH;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $orderBy = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_STAGE_LIBELLE;
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $id,
            $stageCategId,
            $stageNiveau,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStages($prepAttributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function getStageCategorie(int $id): CopsStageCategorieClass
    {
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => $id,
            ]
        ];
        $objs = $this->getStageCategories($attributes);
        return !empty($objs) ? array_shift($objs) : new CopsStageCategorieClass();
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.05
     */
    public function getStageCategories(array $attributes): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $orderBy = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_STAGE_CAT_NAME;
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $id,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStageCategories($prepAttributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.05
     */
    public function getStageSpecialites(array $attributes=[]): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $stageId = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_ID] ?? self::SQL_JOKER_SEARCH;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $orderBy = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID;
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $id,
            $stageId,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getStageSpecialites($prepAttributes);
    }

}
