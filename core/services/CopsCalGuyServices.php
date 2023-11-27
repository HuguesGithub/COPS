<?php
namespace core\services;

use core\daoimpl\CopsCalGuyDaoImpl;
use core\domain\CopsCalGuyClass;

/**
 * Classe CopsCalGuyServices
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalGuyServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
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
            $this->objDao = new CopsCalGuyDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_guy
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getCalGuy(int $id): CopsCalGuyClass
    {
        $objs = $this->getCalGuys([self::FIELD_ID=>$id]);
        return empty($objs) ? new CopsCalGuyClass() : array_shift($objs);
    }

    /**
     * @since v1.23.11.25
     */
    public function getCalGuys(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_NAMESET] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_TITLE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_FIRSTNAME] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_LASTNAME] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_GENKEY] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalGuys($prepAttributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function getDistinctCalGuyField(string $field): array
    { return $this->objDao->getDistinctCalGuyField([$field, $field]); }

    /**
     * @since v1.23.11.25
     */
    public function insertCalGuy(CopsCalGuyClass &$obj): void
    { $this->objDao->insertCalGuy($obj); }

    /**
     * @since v1.23.11.25
     */
    public function updateCalGuy(CopsCalGuyClass $obj): void
    { $this->objDao->updateCalGuy($obj); }


    // Reprise de CopsRandomGuyServices

    public function getTripletAdresse(array $attributes): array
    {
        return $this->objDao->getTripletAdresse($attributes);
    }
}
