<?php
namespace core\services;

use core\daoimpl\CopsCalPhoneDaoImpl;
use core\domain\CopsCalPhoneClass;

/**
 * Classe CopsCalPhoneServices
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalPhoneServices extends LocalServices
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
            $this->objDao = new CopsCalPhoneDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_phone
    ////////////////////////////////////

    /**
     * @since v1.23.12.02
     */
    public function getCalPhone(int $id): CopsCalPhoneClass
    {
        $objs = $this->getCalPhones([self::FIELD_ZIP=>$id]);
        return empty($objs) ? new CopsCalPhoneClass() : array_shift($objs);
    }

    /**
     * @since v1.23.12.02
     */
    public function getCalPhones(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_CITY_NAME] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getCalPhones($prepAttributes);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertCalPhone(CopsCalPhoneClass &$obj): void
    { $this->objDao->insertCalPhone($obj); }

    /**
     * @since v1.23.12.02
     */
    public function updateCalPhone(CopsCalPhoneClass $obj): void
    { $this->objDao->updateCalPhone($obj); }

    /**
     * @since v1.23.12.02
     */
    public function getDistinctFieldValues(string $field): array
    { return $this->objDao->getDistinctFieldValues(new CopsCalPhoneClass(), [$field]); }
}
