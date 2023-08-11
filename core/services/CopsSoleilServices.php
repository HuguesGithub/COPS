<?php
namespace core\services;

use core\domain\CopsSoleilClass;
use core\daoimpl\CopsSoleilDaoImpl;

/**
 * Classe CopsSoleilServices
 * @author Hugues
 * @since v1.23.04.26
 * @version v1.23.08.12
 */
class CopsSoleilServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.04.26
     * @version v1.23.07.29
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
            $this->objDao = new CopsSoleilDaoImpl();
        }
    }

    ////////////////////////////////////
    // WP_7_COPS_SOLEIL
    ////////////////////////////////////
    /**
     * @since v1.23.04.26
     * @version v1.23.08.12
     */
    public function getSoleil(string $strJour): CopsSoleilClass
    {
        $objs = $this->getSoleils([self::FIELD_DATE_SOLEIL => $strJour]);
        return empty($objs) ? new CopsSoleilClass() : array_shift($objs);
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.08.12
     */
    public function getSoleils(array $attributes): array
    {
        $prepAttributes = [
            $attributes[self::FIELD_DATE_SOLEIL] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_SOLEIL,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getSoleils($prepAttributes);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.08.12
     */
    public function getSoleilsIntervalle(array $attributes): array
    {
        $prepAttributes = [
            $attributes[self::CST_STARTDATE] ?? self::CST_FIRST_DATE,
            $attributes[self::CST_ENDDATE] ?? self::CST_LAST_DATE,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_SOLEIL,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getSoleilsIntervalle($prepAttributes);
    }
    ////////////////////////////////////


}
