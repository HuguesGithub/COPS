<?php
namespace core\services;

use core\domain\CopsMeteoClass;
use core\domain\CopsSoleilClass;
use core\daoimpl\CopsMeteoDaoImpl;

/**
 * Classe CopsMeteoServices
 * @author Hugues
 * @since 1.22.04.29
 * @version v1.23.08.12
 */
class CopsMeteoServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version v1.23.07.29
     * @since 1.22.04.29
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
            $this->objDao = new CopsMeteoDaoImpl();
        }
    }

    ////////////////////////////////////
    // WP_7_COPS_METEO
    ////////////////////////////////////
    /**
     * @since v1.23.04.29
     * @version v1.23.08.12
     */
    public function getMeteos(array $params): array
    {
        $prepAttributes = [
            $params[self::FIELD_DATE_METEO] ?? self::SQL_JOKER_SEARCH,
            $params[self::SQL_ORDER_BY] ?? self::FIELD_DATE_METEO,
            $params[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $params[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getMeteos($prepAttributes);
    }


    /**
     * @since v1.23.04.29
     * @version v1.23.07.29
     */
    public function insertMeteo(CopsMeteoClass $objCopsMeteo): void
    { $this->objDao->insertMeteo($objCopsMeteo); }

    ////////////////////////////////////

}
