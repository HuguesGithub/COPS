<?php
namespace core\services;

use core\daoimpl\CopsRandomGuyDaoImpl;
use core\domain\CopsCalPhoneClass;
use core\domain\CopsCalZipCodeClass;

/**
 * Classe CopsRandomGuyServices
 * @author Hugues
 * @since v1.23.09.16
 */
class CopsRandomGuyServices extends LocalServices
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
            $this->objDao = new CopsRandomGuyDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_cal_random_guy
    ////////////////////////////////////

    /**
     * @since v1.23.09.16
     */
    public function getGuys(array $attributes, bool $blnCtrl=false): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_ZIPCODE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 99999,
        ];
        return $this->objDao->getGuys($prepAttributes, $blnCtrl);
    }

    ////////////////////////////////////
    // wp_7_cops_cal_zipcode
    ////////////////////////////////////

    /**
     * @since v1.23.09.16
     */
    public function getZipCode(int $zip): CopsCalZipCodeClass
    {
        $objs = $this->getZipCodes([self::FIELD_ZIP=>$zip]);
        return empty($objs) ? new CopsCalZipCodeClass() : array_shift($objs);
    }

    /**
     * @since v1.23.09.16
     */
    public function getZipCodes(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ZIP] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_PRIMARY_CITY] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_ZIP,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getZipCodes($prepAttributes);
    }

    ////////////////////////////////////
    // wp_7_cops_cal_phone
    ////////////////////////////////////

    /**
     * @since v1.23.09.16
     */
    public function getPhone(string $phoneId): CopsCalPhoneClass
    {
        $objs = $this->getPhones([self::FIELD_PHONE_ID=>$phoneId]);
        return empty($objs) ? new CopsCalPhoneClass() : array_shift($objs);
    }

    /**
     * @since v1.23.09.16
     */
    public function getPhones(array $attributes): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_PHONE_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_CITY_NAME] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_PHONE_ID,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getPhones($prepAttributes);
    }

}
