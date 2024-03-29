<?php
namespace core\services;

use core\daoimpl\CopsCalAddressDaoImpl;
use core\domain\CopsCalAddressClass;

/**
 * Classe CopsCalAddressServices
 * @author Hugues
 * @since v1.23.11.25
 * @version v1.23.12.02
 */
class CopsCalAddressServices extends LocalServices
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
            $this->objDao = new CopsCalAddressDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_address
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     * @version v1.23.12.02
     */
    public function getCalAddresses(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ST_DIRECTION] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ST_NAME] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ST_SUFFIX] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ST_SUF_DIRECTION] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ZIPCODE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalAddresses($prepAttributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function insertCalAddress(CopsCalAddressClass &$obj): void
    { $this->objDao->insertCalAddress($obj); }

    /**
     * @since v1.23.11.25
     */
    public function updateCalAddress(CopsCalAddressClass $obj): void
    { $this->objDao->updateCalAddress($obj); }

    /**
     * @since v1.23.12.02
     */
    public function getDistinctFieldValues(string $field): array
    { return $this->objDao->getDistinctFieldValues(new CopsCalAddressClass(), [$field]); }

}
