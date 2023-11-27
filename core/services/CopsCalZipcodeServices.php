<?php
namespace core\services;

use core\daoimpl\CopsCalZipcodeDaoImpl;
use core\domain\CopsCalZipcodeClass;

/**
 * Classe CopsCalZipcodeServices
 * @author Hugues
 * @since v1.23.11.25
 */
class CopsCalZipcodeServices extends LocalServices
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
            $this->objDao = new CopsCalZipcodeDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_zipcode
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getCalZipcode(int $zip): CopsCalZipcodeClass
    {
        $objs = $this->getCalZipcodes([self::FIELD_ZIP=>$zip]);
        return empty($objs) ? new CopsCalZipcodeClass() : array_shift($objs);
    }

    /**
     * @since v1.23.11.25
     * @version v1.23.12.02
     */
    public function getCalZipcodes(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ZIP] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_PRIMARY_CITY] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ZIP,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalZipcodes($prepAttributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalZipcode(CopsCalZipcodeClass &$obj): void
    { $this->objDao->insertCalZipcode($obj); }

    /**
     * @since v1.23.11.25
     */
    public function updateCalZipcode(CopsCalZipcodeClass $obj): void
    { $this->objDao->updateCalZipcode($obj); }

    /**
     * @since v1.23.12.02
     */
    public function getDistinctFieldValues(string $field): array
    { return $this->objDao->getDistinctFieldValues(new CopsCalZipcodeClass(), [$field]); }
}
