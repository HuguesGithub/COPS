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
        $this->Dao = null;
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
        if ($this->Dao==null) {
            $this->DAo = new CopsSoleilDaoImpl();
        }
        $prepAttributes = [$strJour];
        $rows = $this->Dao->getSoleil($prepAttributes);
        return new CopsSoleilClass($rows[0]);
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function getSoleils(array $attributes): array
    {
        if ($this->Dao==null) {
            $this->DAo = new CopsSoleilDaoImpl();
        }
        $prepAttributes = [
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_SOLEIL] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_SOLEIL,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getSoleils($prepAttributes);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getSoleilsIntervalle(array $attributes): array
    {
        if ($this->Dao==null) {
            $this->DAo = new CopsSoleilDaoImpl();
        }
        $startDate = $attributes[self::SQL_WHERE_FILTERS][self::CST_STARTDATE] ?? self::CST_FIRST_DATE;
        $endDate = $attributes[self::SQL_WHERE_FILTERS][self::CST_ENDDATE] ?? self::CST_LAST_DATE;

        $prepAttributes = [
            $startDate,
            $endDate,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DATE_SOLEIL,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getSoleilsIntervalle($prepAttributes);
    }
    ////////////////////////////////////


}
