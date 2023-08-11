<?php
namespace core\services;

use core\domain\CopsLuneClass;
use core\daoimpl\CopsLuneDaoImpl;

/**
 * Classe CopsLuneServices
 * @author Hugues
 * @since v1.23.04.27
 * @version v1.23.08.12
 */
class CopsLuneServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.04.27
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
            $this->objDao = new CopsLuneDaoImpl();
        }
    }

    ////////////////////////////////////
    // WP_7_COPS_LUNE
    ////////////////////////////////////

    /**
     * @since v1.23.04.27
     * @version v1.23.08.12
     */
    public function getLunes(array $attributes): array
    {
        $prepAttributes = [
            $attributes[self::CST_STARTDATE] ?? self::CST_FIRST_DATE,
            $attributes[self::CST_ENDDATE] ?? self::CST_LAST_DATE,
            $attributes[self::FIELD_TYPE_LUNE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_LUNE,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getLunes($prepAttributes);
    }
    ////////////////////////////////////


}
