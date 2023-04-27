<?php
namespace core\services;

use core\domain\CopsLuneClass;
use core\daoimpl\CopsLuneDaoImpl;

/**
 * Classe CopsLuneServices
 * @author Hugues
 * @since v1.23.04.27
 * @version v1.23.04.30
 */
class CopsLuneServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function __construct()
    {
        $this->Dao = new CopsLuneDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_LUNE
    ////////////////////////////////////

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getMoons(array $attributes): array
    {
        $startDate = $attributes[self::SQL_WHERE_FILTERS]['startDate'] ?? '2030-01-01';
        $endDate = $attributes[self::SQL_WHERE_FILTERS]['endDate'] ?? '2035-12-31';
        $typeLune = $attributes[self::SQL_WHERE_FILTERS]['typeLune'] ?? '%';

        $prepAttributes = [
            $startDate,
            $endDate,
            $typeLune,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_LUNE,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getMoons($prepAttributes);
    }
    ////////////////////////////////////


}
