<?php
namespace core\services;

use core\domain\CopsSoleilClass;
use core\daoimpl\CopsSoleilDaoImpl;

/**
 * Classe CopsSoleilServices
 * @author Hugues
 * @since v1.23.04.26
 * @version v1.23.04.30
 */
class CopsSoleilServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function __construct()
    {
        $this->Dao = new CopsSoleilDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_SOLEIL
    ////////////////////////////////////
    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getSoleil(string $strJour): CopsSoleilClass
    {
        $prepAttributes = [$strJour];
        $rows = $this->Dao->getSoleil($prepAttributes);
        return new CopsSoleilClass($rows[0]);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getSoleilsIntervalle(array $attributes): array
    {
        $startDate = $attributes[self::SQL_WHERE_FILTERS]['startDate'] ?? '2030-01-01';
        $endDate = $attributes[self::SQL_WHERE_FILTERS]['endDate'] ?? '2035-12-31';

        $prepAttributes = [];
        $prepAttributes[self::SQL_WHERE_FILTERS] = [$startDate, $endDate];
        $prepAttributes[] = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_SOLEIL;
        $prepAttributes[] = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        $prepAttributes[] = $attributes[self::SQL_LIMIT] ?? 9999;
        return $this->Dao->getSoleilsIntervalle($prepAttributes);
    }
    ////////////////////////////////////


}
