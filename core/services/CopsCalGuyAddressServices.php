<?php
namespace core\services;

use core\daoimpl\CopsCalGuyAddressDaoImpl;
use core\domain\CopsCalGuyAddressClass;

/**
 * Classe CopsCalGuyAddressServices
 * @author Hugues
 * @since v1.23.11.25
 * @version v1.23.12.02
 */
class CopsCalGuyAddressServices extends LocalServices
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
            $this->objDao = new CopsCalGuyAddressDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_guy_address
    ////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getCalGuyAddresses(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_GUY_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ADDRESS_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalGuyAddresses($prepAttributes);
    }

    /**
     * @since v1.23.11.25
     * @version v1.23.12.02
     */
    public function insertCalGuyAddress(CopsCalGuyAddressClass &$obj): void
    { $this->objDao->insertCalGuyAddress($obj); }

    /**
     * @since v1.23.11.25
     */
    public function updateCalGuyAddress(CopsCalGuyAddressClass $obj): void
    { $this->objDao->updateCalGuyAddress($obj); }

    /**
     * @since v1.23.12.02
     */
    public function deleteCalGuyAddress(CopsCalGuyAddressClass $obj): void
    { $this->objDao->deleteCalGuyAddress($obj); }

}
