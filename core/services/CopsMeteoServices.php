<?php
namespace core\services;

use core\domain\CopsMeteoClass;
use core\domain\CopsSoleilClass;
use core\daoimpl\CopsMeteoDaoImpl;

/**
 * Classe CopsMeteoServices
 * @author Hugues
 * @since 1.22.04.29
 * @version v1.23.06.18
 */
class CopsMeteoServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version 1.22.04.29
     * @since 1.22.04.29
     */
    public function __construct()
    {
        $this->Dao = null;
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_METEO
    ////////////////////////////////////
    /**
     * @since v1.23.04.29
     * @version v1.23.04.30
     */
    public function getMeteos(array $params): array
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsMeteoDaoImpl();
        }
        $prepAttributes = [];
        $prepAttributes[] = $params[self::SQL_WHERE_FILTERS][self::FIELD_DATE_METEO] ?? self::SQL_JOKER_SEARCH;
        $prepAttributes[] = $params[self::SQL_ORDER_BY] ?? self::FIELD_DATE_METEO;
        $prepAttributes[] = $params[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        $prepAttributes[] = $params[self::SQL_LIMIT] ?? 9999;
        return $this->Dao->getMeteos($prepAttributes);
    }


    /**
     * @since v1.23.04.29
     * @version v1.23.06.18
     */
    public function insertMeteo(CopsMeteoClass $objCopsMeteo): void
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsMeteoDaoImpl();
        }
        $this->Dao->insertMeteo($objCopsMeteo);
    }

    ////////////////////////////////////

}
