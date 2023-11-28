<?php
namespace core\services;

use core\daoimpl\CopsCalGuyPhoneDaoImpl;
use core\domain\CopsCalGuyPhoneClass;

/**
 * Classe CopsCalGuyPhoneServices
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalGuyPhoneServices extends LocalServices
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
            $this->objDao = new CopsCalGuyPhoneDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_guy_phone
    ////////////////////////////////////

    /**
     * @since v1.23.12.02
     */
    public function getCalGuyPhones(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_GUY_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_PHONENUMBER] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalGuyPhones($prepAttributes);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertCalGuyPhone(CopsCalGuyPhoneClass &$obj): void
    { $this->objDao->insertCalGuyPhone($obj); }

    /**
     * @since v1.23.12.02
     */
    public function updateCalGuyPhone(CopsCalGuyPhoneClass $obj): void
    { $this->objDao->updateCalGuyPhone($obj); }

    /**
     * @since v1.23.12.02
     */
    public function deleteCalGuyPhone(CopsCalGuyPhoneClass $obj): void
    { $this->objDao->deleteCalGuyPhone($obj); }

}
